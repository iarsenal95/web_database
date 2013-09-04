#include "Indexer.h"

#include <fstream>

using std::ifstream;
using std::ostream;

#include <iostream>
#include <string>
#include <vector>
#include <map>
#include <math.h>
#include <utility>
#include <sstream>
#include <ctype.h>
#include <algorithm>
using std::cout;
using std::string;
using std::map;
using std::vector;
using std::pair;
using std::stringstream;
using std::endl;
using std::cerr;

typedef pair<string, int> Mypair;

double getNorm(int docid, map<int, map<string, int>* > &docAndTerm, map<string, TermInfo> &result){
	map<int, map<string, int>* >::iterator it = docAndTerm.find(docid);
	if (it == docAndTerm.end())
		return -1;
	else{
		map<string, int>::iterator map_it;
		double sum = 0;
		for(map_it = it->second->begin(); map_it != it->second->end(); ++map_it){
			map<string, TermInfo>::iterator result_it = result.find(map_it->first);
			double idftemp = result_it->second.idf;
			sum += pow((double)map_it->second, 2)*pow(idftemp, 2);
		}
		return sqrt(sum);
	}
}

bool stopwords(map<string, bool> &mymap){
	const char *stopword = "stopwords.txt";
    ifstream content(stopword);
    if (!content.is_open()) {
        cerr << "Error opening file: " << stopword << endl;
        return false;
    }
	string word;
	while(getline(content, word)){
		mymap.insert(pair<string, bool>(word, true));
	}
	content.close();
	return true;
} 


// Reads content from the supplied input file stream, and transforms the
// content into the actual on-disk inverted index file.
void Indexer::index(ifstream& content, ostream& outfile)
{
    // Fill in this method to parse the content and build your
    // inverted index file.
	string line;
	vector<string> docs;
	map<string, bool> stopword;
	stopwords(stopword);
	map<string, TermInfo> result;
	map<int, map<string, int>* > docAndTerm;	// to help calculate the norm
	// read in the input file and save the content in a vector
	while(getline(content, line)){
		docs.push_back(line);
	}
	vector<string>::iterator doc_it;
	int docid = 1;
	for (doc_it = docs.begin(); doc_it != docs.end(); ++doc_it){ 
		stringstream os;
		os << *doc_it;
		string term;
		map<string, int>* mymap = new map<string, int>;    // flag to record the totalOccur


		// if result has this term, update the freq info
		// if not, insert the new term 
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
			if (term.length() < 1)
				continue;
			if (stopword.find(term) != stopword.end()){
				continue;
			}
			map<string, TermInfo>::iterator result_it = result.find(term);
			if (result_it == result.end()){
				mymap->insert(pair<string, int>(term, 1));
				TermInfo terminfo;
				terminfo.totalOccur = 1;
				TermInfoInDoc termindoc;
				termindoc.doc_id = docid;
				termindoc.tf = 1;
				terminfo.info.push_back(termindoc);
				result.insert(pair<string, TermInfo>(term, terminfo));
			}
			else{
				map<string, int>::iterator it = mymap->find(term);
				// if this word occurs first time in this doc
				if (it == mymap->end()){
					mymap->insert(pair<string, int>(term, 1));
					result_it->second.totalOccur++;
					TermInfoInDoc termindoc;
					termindoc.doc_id = docid;
					termindoc.tf = 1;
					result_it->second.info.push_back(termindoc); 
				}
				// if this word occurs before in this doc
				else{
					it->second++;
					vector<TermInfoInDoc>::iterator temp;
					vector<TermInfoInDoc>* vec = &(result_it->second.info);
					for(temp = vec->begin(); temp != vec->end(); ++temp){
						if (temp->doc_id == docid){
							temp->tf++;
							break;
						}
					}
				}
			}
			docAndTerm.insert(pair<int, map<string, int>* >(docid, mymap));	
		}
		docid++;
	}
	
	
	// now update the ifd and norm
	int totalDoc = docid-1;
	map<string, TermInfo>::iterator it;
	for (it = result.begin(); it != result.end(); ++it){
		it->second.idf= log((double)totalDoc/(it->second.totalOccur));
	}
	for (it = result.begin(); it != result.end(); ++it){
		vector<TermInfoInDoc>::iterator it_1;
		outfile << it->first << " " << it->second.idf << " " << it->second.totalOccur << " ";
		for(it_1 = it->second.info.begin(); it_1 != it->second.info.end(); ++it_1){
			it_1->norm = getNorm(it_1->doc_id, docAndTerm, result);
			outfile<<it_1->doc_id << " " << it_1->tf << " " << it_1->norm << " ";
		}
		outfile << endl;
	}
	return;
	
}
