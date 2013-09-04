#include "Index_server.h"

#include <cassert>
#include <cstdlib>
#include <cstring>
#include <fstream>
#include <iostream>
#include <pthread.h>
#include <sstream>
#include <math.h>
#include <functional>

#include "mongoose.h"

using std::cerr;
using std::cout;
using std::endl;
using std::ifstream;
using std::ostream;
using std::ostringstream;
using std::string;
using std::vector;
using std::stringstream;
using std::pair;

namespace {
    int handle_request(mg_connection *);
    int get_param(const mg_request_info *, const char *, string&);
    string get_param(const mg_request_info *, const char *);
    string to_json(const vector<Query_hit>&);

    ostream& operator<< (ostream&, const Query_hit&);
}

pthread_mutex_t mutex;

// Runs the index server on the supplied port number.
void Index_server::run(int port)
{
    // List of options. Last element must be NULL
    ostringstream port_os;
    port_os << port;
    string ps = port_os.str();
    const char *options[] = {"listening_ports",ps.c_str(),0};

    // Prepare callback structure. We have only one callback, the rest are NULL.
    mg_callbacks callbacks;
    memset(&callbacks, 0, sizeof(callbacks));
    callbacks.begin_request = handle_request;

    // Initialize the global mutex lock that effectively makes this server
    // single-threaded.
    pthread_mutex_init(&mutex, 0);

    // Start the web server
    mg_context *ctx = mg_start(&callbacks, this, options);
    if (!ctx) {
        cerr << "Error starting server." << endl;
        return;
    }

    pthread_exit(0);
}

// Calculate the score
double Index_server::score(double* query_weight, double* weight, int len){
	double sum = 0;
	for (int i = 0; i < len; i++){
		sum += query_weight[i]* weight[i];
	}
	return sum;
}

// Load index data from the file of the given name.
void Index_server::init(ifstream& infile)
{
    // Fill in this method to load the inverted index from disk.
	string line;
	while(getline(infile, line)){
		stringstream os;
		os << line;
		string term;
		vector<string> tuple;	//save the terms
		while(os >> term){
			tuple.push_back(term);
		}
		//invertedIndex
		TermInfo terminfo;
		terminfo.idf = atof(tuple[1].c_str());
		int num = atoi(tuple[2].c_str());
		terminfo.totalOccur = num;
		for(int i = 0; i < num; i++){
			TermInfoInDoc termdoc;
			const char* temp_0 = tuple[3+3*i].c_str();
			char* temp = new char[strlen(temp_0)+1];
			for(unsigned int j = 0 ; j < strlen(temp_0); ++j){
				temp[j] = temp_0[j];
			}
			temp[strlen(temp_0)] = '\0'; 
			termdoc.doc_id = (const char*)temp;
			termdoc.tf = atoi(tuple[4+3*i].c_str());
			termdoc.norm = atof(tuple[5+3*i].c_str());
			terminfo.info.push_back(termdoc);
		}
		invertedIndex.insert(pair<string, TermInfo>(tuple[0], terminfo));
	}
}

struct StrCompare : public std::binary_function<const char*, const char*, bool> {
public:
    bool operator() (const char* str1, const char* str2) const
    { return std::strcmp(str1, str2) < 0; }
};

typedef std::map<const char*, double*, StrCompare> MyMap;

// Search the index for documents matching the query. The results are to be
// placed in the supplied "hits" vector, which is guaranteed to be empty when
// this method is called.
void Index_server::process_query(const string& query, vector<Query_hit>& hits)
{
    cout << "Processing query '" << query << "'" << endl;

    // Fill this in to process queries.
	if (query.length() == 0)
		return;
	MyMap doc_weight;
	map<string, int> termfreq;
	int len = 0;
	stringstream os;
	os << query;
	string term;
	vector<string> tuple;
	while(os >> term){
		unsigned int i = 0;
		while(i < term.length()){
    		if (!isalnum(term[i]))
        		term.erase(i, 1);
			else{
				string ss = string(1, (char)tolower(term[i]));
				term.replace(i, 1, ss);	
				++i;
			}	
		}
		if (term.length() > 0){
			tuple.push_back(term);
			map<string, int>::iterator it = termfreq.find(term);
			if (it == termfreq.end()){
				termfreq.insert(pair<string, int>(term, 1));
			}
			else
				it->second++;
		}
	}
	len = (int)tuple.size();
	double* query_weight = new double[len];
	memset(query_weight, 0, sizeof(double)*len);
	// caculate the norm of query
	double query_norm;
	for(int i = 0; i < len; i++){
		term = tuple[i];
		map<string, TermInfo>::iterator index_it = invertedIndex.find(term);
		if (index_it == invertedIndex.end())
			continue;
		else
			query_norm += pow(termfreq.find(term)->second, 2)*pow(index_it->second.idf, 2);
	}
	query_norm = sqrt(query_norm);
	// caculate the weight	 
	for(int i = 0; i < len; i++){
		term = tuple[i];
		map<string, TermInfo>::iterator index_it = invertedIndex.find(term);
		if (index_it == invertedIndex.end()){
			query_weight[i] = 0;
			continue;
		}
		else{
			double idftemp = index_it->second.idf;
			query_weight[i] = idftemp*(termfreq.find(term)->second)/query_norm;
			// caculate every docid's weight of term
			vector<TermInfoInDoc>* vec = &(index_it->second.info);
			vector<TermInfoInDoc>::iterator info_it;
			for(info_it = vec-> begin(); info_it != vec->end(); ++info_it){
				const char* id = info_it->doc_id;
				cout << "find the id: " << id << endl;
				double weight_temp = (info_it->tf)*idftemp/(info_it->norm);
				MyMap::iterator doc_weight_it = doc_weight.find(id);
				if (doc_weight_it != doc_weight.end()){
					(doc_weight_it->second)[i] = weight_temp;
				}
				else{
					double* temp = new double[len];
					memset(temp, 0, sizeof(double)*len);
					temp[i] = weight_temp;
					doc_weight.insert(pair<const char*, double*>(id, temp));
				}
			}
		}
	}

	/*
	for (int i = 0 ; i < len; i++){
		cout << "query weight:" << query_weight[i]<< endl;
	}*/
	
	// get the score for every docid related
	MyMap::iterator docw_it;
	for(docw_it = doc_weight.begin(); docw_it != doc_weight.end(); ++docw_it){
		const char* id = docw_it->first;
		double* wght = docw_it->second;
		double scr = score(query_weight, wght, len);
		//vector<Query_hit>& hits
		Query_hit hit(id, scr);
		hits.push_back(hit);
		cout << id <<": " << scr << endl;
	}
	return;
}

namespace {
    int handle_request(mg_connection *conn)
    {
        const mg_request_info *request_info = mg_get_request_info(conn);

        if (!strcmp(request_info->request_method, "GET") && request_info->query_string) {
            // Make the processing of each server request mutually exclusive with
            // processing of other requests.

            // Retrieve the request form data here and use it to call search(). Then
            // pass the result of search() to to_json()... then pass the resulting string
            // to mg_printf.
            string query;
            if (get_param(request_info, "q", query) == -1) {
                // If the request doesn't have the "q" field, this is not an index
                // query, so ignore it.
                return 1;
            }

            vector<Query_hit> hits;
            Index_server *server = static_cast<Index_server *>(request_info->user_data);

            pthread_mutex_lock(&mutex);
            server->process_query(query, hits);
            pthread_mutex_unlock(&mutex);

            string response_data = to_json(hits);
            int response_size = response_data.length();

            // Send HTTP reply to the client.
            mg_printf(conn,
                      "HTTP/1.1 200 OK\r\n"
                      "Content-Type: application/json\r\n"
                      "Content-Length: %d\r\n"
                      "\r\n"
                      "%s", response_size, response_data.c_str());
        }

        // Returning non-zero tells mongoose that our function has replied to
        // the client, and mongoose should not send client any more data.
        return 1;
    }

    int get_param(const mg_request_info *request_info, const char *name, string& param)
    {
        const char *get_params = request_info->query_string;
        size_t params_size = strlen(get_params);

        // On the off chance that operator new isn't thread-safe.
        pthread_mutex_lock(&mutex);
        char *param_buf = new char[params_size + 1];
        pthread_mutex_unlock(&mutex);

        param_buf[params_size] = '\0';
        int param_length = mg_get_var(get_params, params_size, name, param_buf, params_size);
        if (param_length < 0) {
            return param_length;
        }

        // Probably not necessary, just a precaution.
        param = param_buf;
        delete[] param_buf;

        return 0;
    }

    // Converts the supplied query hit list into a JSON string.
    string to_json(const vector<Query_hit>& hits)
    {
        ostringstream os;
        os << "{\"hits\":[";
        vector<Query_hit>::const_iterator viter;
        for (viter = hits.begin(); viter != hits.end(); ++viter) {
            if (viter != hits.begin()) {
                os << ",";
            }

            os << *viter;
        }
        os << "]}";

        return os.str();
    }

    // Outputs the computed information for a query hit in a JSON format.
    ostream& operator<< (ostream& os, const Query_hit& hit)
    {
        os << "{" << "\"id\":\"";
        int id_size = strlen(hit.id);
        for (int i = 0; i < id_size; i++) {
            if (hit.id[i] == '"') {
                os << "\\";
            }
            os << hit.id[i];
        }
        return os << "\"," << "\"score\":" << hit.score << "}";
    }
}
