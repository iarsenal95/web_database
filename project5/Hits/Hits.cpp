#include "Hits.h"

#include <cassert>
#include <cstdlib>
#include <cstring>
#include <fstream>
#include <iostream>
#include <sstream>
#include <math.h>
#include <functional>
#include <utility>
#include <map>
#include <vector>
#include <queue>
using namespace std;

//Read in web from inputNet, save the web in outLinks and inLinks and build the index from inputIndex
void Hits::init(ifstream& inputNet, ifstream& inputIndex){
	// build the web 
    string line;
    getline(inputNet, line);
    stringstream os;
	os << line;
	string term;
	os >> term;
	os >> term;
	int lineNum = atoi(term.c_str());
	while(getline(inputNet, line)){
		if(lineNum >= 0){
			lineNum--;
		}
		else{
			stringstream ss;
			ss << line;
			int from, to;
			ss >> from;
			ss >> to;
			map<int, vector<int>* >::iterator it = outLinks.find(from);
			if (it != outLinks.end()){
				it->second->push_back(to);
			}
			else{
				vector<int>* temp = new vector<int>;
				temp->push_back(to);
				outLinks.insert(pair<int, vector<int>* >(from, temp));
			}
			it = inLinks.find(to);
			if (it != inLinks.end()){
				it->second->push_back(from);
			}
			else{
				vector<int>* temp = new vector<int>;
				temp->push_back(from);
				inLinks.insert(pair<int, vector<int>* >(to, temp));
			}	
		}
	}
	// build the index 	
	while(getline(inputIndex, line)){
		stringstream ss;
		ss << line;
		string word;
		int fid;
		ss >> word;
		ss >> fid;
		unsigned int i = 0;
		while(i < word.length()){
				string ss = string(1, (char)tolower(word[i]));
				word.replace(i, 1, ss);	
				++i;	
		}
		map<string, vector<int>* >::iterator it = index.find(word);
		if (it != index.end()){
			it->second->push_back(fid);
		}
		else{
			vector<int>* temp = new vector<int>;
			temp->push_back(fid);
			index.insert(pair<string, vector<int>* >(word, temp));
		}
	}
	return;
}

// caculate the hub and auth scores for pages in the baseSet, used for "-k" condition
// ouput in saved in double* hub, double* hub
// baseMap is an array which provides the mapping of (index, pageid) 
void Hits::getHits(double* hub, double* auth, int* baseMap, int len){
	double sum_hub = 0;
	double sum_auth = 0;
	double* hubtemp = new double[len];
	double* authtemp = new double[len];
	for(int i = 0 ; i < len; ++i){
		map<int, vector<int>* >::iterator it = newoutlink.find(baseMap[i]);
		if (it == newoutlink.end()){
			hubtemp[i] = hub[i];
		}
		else{
			hubtemp[i] = 0;
			vector<int>* temp = it->second;
			for (vector<int>::iterator it_v = temp->begin(); it_v != temp->end(); ++it_v){
				int index = baseSet.find(*it_v)->second;
				hubtemp[i] += auth[index];
			}
		}
		sum_hub += hubtemp[i]*hubtemp[i];
	}
	for(int i = 0 ; i < len; ++i){
		map<int, vector<int>* >::iterator it = newinlink.find(baseMap[i]);
		if (it == newinlink.end()){
			authtemp[i] = auth[i];
		}
		else{
			authtemp[i] = 0;
			vector<int>* temp = it->second;
			for (vector<int>::iterator it_v = temp->begin(); it_v != temp->end(); ++it_v){
				int index = baseSet.find(*it_v)->second;
				authtemp[i] += hub[index];
			}
		}
		sum_auth += authtemp[i]*authtemp[i];
	}
	for(int i = 0 ; i < len; ++i){
		hub[i] = hubtemp[i]/sqrt(sum_hub);
		auth[i] = authtemp[i]/sqrt(sum_auth);
	}
	delete[] authtemp;
	delete[] hubtemp;
	return;
}

// get the hub and auth scores for baseSet, used for "-converge" condition	
// ouput in saved in double* hub, double* hub
// baseMap is an array which provides the mapping of (index, pageid) 
void Hits::getHitsConv(double* hub, double* auth, int* baseMap, int len, double stopPara){
	bool stop = false;
	double* hubtemp = new double[len];
	double* authtemp = new double[len];
	int count = 0;
	// stop the loop when every change of hub and auth score is less than stopPara
	while(!stop){
		double sum_hub = 0;
		double sum_auth = 0;
		count++;
		stop = true;
		for(int i = 0 ; i < len; ++i){
			map<int, vector<int>* >::iterator it = newoutlink.find(baseMap[i]);
			if (it == newoutlink.end()){
				hubtemp[i] = hub[i];
			}
			else{
				hubtemp[i] = 0;
				vector<int>* temp = it->second;
				for (vector<int>::iterator it_v = temp->begin(); it_v != temp->end(); ++it_v){
					int index = baseSet.find(*it_v)->second;
					hubtemp[i] += auth[index];
				}
			}
			sum_hub += pow(hubtemp[i], 2);
		}
		for(int i = 0 ; i < len; ++i){
			map<int, vector<int>* >::iterator it = newinlink.find(baseMap[i]);
			if (it == newinlink.end()){
				authtemp[i] = auth[i];
			}
			else{
				authtemp[i] = 0;
				vector<int>* temp = it->second;
				for (vector<int>::iterator it_v = temp->begin(); it_v != temp->end(); ++it_v){
					int index = baseSet.find(*it_v)->second;
					authtemp[i] += hub[index];
				}
			}
			sum_auth += pow(authtemp[i], 2);
		}
		for(int i = 0 ; i < len; ++i){
			hubtemp[i] = hubtemp[i]/sqrt(sum_hub);
			authtemp[i] = authtemp[i]/sqrt(sum_auth);
			if (fabs(hubtemp[i]-hub[i])/hub[i] > stopPara)
				stop = false;
			if (fabs(authtemp[i]-auth[i])/auth[i] > stopPara)
				stop = false;
			hub[i] = hubtemp[i];
			auth[i] = authtemp[i];
		}
	}
	delete[] authtemp;
	delete[] hubtemp;
	return;	
}

// build the newinlink and newoutlink
void subWeb(int id1, int id2, map<int, vector<int>* >& link){
	map<int, vector<int>* >::iterator it = link.find(id1);
	if (it == link.end()){
		vector<int>* temp = new vector<int>;
		temp->push_back(id2);
		link.insert(pair<int, vector<int>* >(id1, temp));
	}
	else{
		it->second->push_back(id2);
	}
	return;
}

// update the baseSet, newinlink and newoutlink
void Hits::baseSetHelper(vector<int>* v, vector<int>::iterator it, int num, int docid, bool isOut){
	while(it != v->end() && num > 0){
		--num;
		// docid -> *it
		if (isOut){
			subWeb(docid, *it, newoutlink);
			subWeb(*it, docid, newinlink);
		}
		// *it -> docid
		else{
			subWeb(*it, docid, newoutlink);
			subWeb(docid, *it, newinlink);
		}
		if (baseSet.find(*it) == baseSet.end())
			baseSet.insert(pair<int, int>(*it, 0));
		++it;
	}
	return;
}

void Hits::getBaseSet(string query, int h_value){
	map<int, bool>* rootSet = NULL;
	map<int, bool>* rootTemp = NULL;
	stringstream ss;
	ss << query;
	string term;
	cout << "Start processing query: " << query << endl;
	// first get the rootSet
	while(ss >> term){
		unsigned int i = 0;
		while(i < term.length()){
				string ss = string(1, (char)tolower(term[i]));
				term.replace(i, 1, ss);	
				++i;	
		}
		map<string, vector<int>* >::iterator it = index.find(term);
		if (rootSet == NULL){
			rootSet = new map<int, bool>;
			if (it != index.end()){
				vector<int>* temp = it->second;
				for (vector<int>::iterator it_v = temp->begin(); it_v != temp->end(); ++it_v){
					int rootDid = *it_v;
					if (rootSet->find(rootDid) == rootSet->end()){
						rootSet->insert(pair<int, bool>(rootDid, true));
					}
				}
			}
			else{
				cout << "Index of " << term << " not found." << endl;
				return;
			}
		}
		else{
			if (rootSet->size() == 0){
				cout << "Document contains all query words does not exist." << endl;
				return;
			}
			rootTemp = new map<int, bool>;
			if (it != index.end()){
				vector<int>* temp = it->second;
				for (vector<int>::iterator it_v = temp->begin(); it_v != temp->end(); ++it_v){
					int rootDid = *it_v;
					if (rootSet->find(rootDid) != rootSet->end()){
						rootTemp->insert(pair<int, bool>(rootDid, true));
					}
				}
			}
			else{
				cout << "Index of " << term << " not found." << endl;
				return;
			}
			map<int, bool>* temp = rootSet;
			rootSet = rootTemp;
			delete temp;
		}
			
	}
	// get the top h_value pages in rootSet and get the baseSet
	map<int, bool>::iterator it_rootSet = rootSet->begin();
	while(h_value > 0 && it_rootSet != rootSet->end()){
		int num = 50;
		h_value--;
		int rootDid = it_rootSet->first;
		++it_rootSet;
		if (baseSet.find(rootDid) == baseSet.end()){
			baseSet.insert(pair<int, int>(rootDid, 0));
		}
		// find the inlinks and outlinks of this id
		map<int, vector<int>* >::iterator it_out = outLinks.find(rootDid);
		map<int, vector<int>* >::iterator it_in = inLinks.find(rootDid);
		if (it_out == outLinks.end() && it_in == inLinks.end()){
			cout << "Does not find this id" << endl;
			continue;
		}
		else if (it_out == outLinks.end()){
			// find 50 in inLinks
			vector<int>* v_in = it_in->second;
			baseSetHelper(v_in, v_in->begin(), num, rootDid, false);
		}
		else if (it_in == inLinks.end()){
			// find 50 in outLinks
			vector<int>* v_out = it_out->second;
			baseSetHelper(v_out, v_out->begin(), num, rootDid, true);	
		}
		else{
			vector<int>* v_out = it_out->second;
			vector<int>* v_in = it_in->second;
			vector<int>::iterator it_v_in = v_in->begin();
			vector<int>::iterator it_v_out = v_out->begin();
			while(it_v_out != v_out->end() && it_v_in != v_in->end()){
				if (num == 0)
					break;
				--num;
				if (*it_v_out < *it_v_in){
					// rootDid -> *it_v_out 
					subWeb(rootDid, *it_v_out, newoutlink);
					subWeb(*it_v_out, rootDid, newinlink);
					if (baseSet.find(*it_v_out) == baseSet.end())
						baseSet.insert(pair<int, int>(*it_v_out, 0));
					++it_v_out;
					
				}
				else if (*it_v_out > *it_v_in){
					// *it_v_in -> rootDid
					subWeb(rootDid, *it_v_in, newinlink);
					subWeb(*it_v_in, rootDid, newoutlink);
					if (baseSet.find(*it_v_in) == baseSet.end())
						baseSet.insert(pair<int, int>(*it_v_in, 0));
					++it_v_in;
				}
				else{
					subWeb(rootDid, *it_v_out, newoutlink);
					subWeb(*it_v_out, rootDid, newinlink);
					subWeb(rootDid, *it_v_in, newinlink);
					subWeb(*it_v_in, rootDid, newoutlink);
					if (baseSet.find(*it_v_out) == baseSet.end())
						baseSet.insert(pair<int, int>(*it_v_out, 0));
					++it_v_out;
					++it_v_in;
				}
			}
			if (num == 0)
				continue;
			else if (it_v_out == v_out->end()){
				baseSetHelper(v_in, it_v_in, num, rootDid, false);
			}
			else if (it_v_in == v_in->end()){
				baseSetHelper(v_out, it_v_out, num, rootDid, true);
			}
		}
	}
	return;
}	

void Hits::process_query(int h_value, string stopCrit, double stopPara, string query, std::ostream& outfile){
	getBaseSet(query, h_value);  // get the baseSet
	if (baseSet.size() == 0)
		return;
	int n = (int)baseSet.size();
	cout << "-------------------------" << endl;
	cout << "Get baseSet is completed!" << endl;
	cout << "The size of baseSet is: " << n << endl;
	int* baseMap = new int[n]; 
	int temp = 0;
	//baseSet save the information: what is the index of a doc_id in the vector baseMap
	for (map<int, int>::iterator it = baseSet.begin(); it != baseSet.end(); ++it){
		baseMap[temp] = it->first;
		it->second = temp;
		temp++;
	}
	double* hub = new double[n];
	double* auth = new double[n];
	for(int i = 0 ; i < n; ++i){
		hub[i] = 1;
		auth[i] = 1;
	}
	if (stopCrit == "-k"){
		int iterNum = (int)stopPara;
		cout << "Start getHits: " << endl;
		while(iterNum > 0){
			getHits(hub, auth, baseMap, n);
			iterNum--;
		}
	}
	else if (stopCrit == "-converge"){
		cout << "Start getHitsConv: " << endl;
		getHitsConv(hub, auth, baseMap, n, stopPara);
	}
	for (int i = 0; i < n; ++i){
		outfile << baseMap[i] << "," << hub[i] << "," << auth[i] << endl;
	}
	delete[] hub;
	delete[] auth;
	delete[] baseMap;
	map<int, vector<int>* >::iterator it_temp = newinlink.begin();
	while(it_temp != newinlink.end()){
		delete it_temp->second;
		it_temp++;
	}
	it_temp = newoutlink.begin();
	while(it_temp != newoutlink.end()){
		delete it_temp->second;
		it_temp++;
	}
	newinlink.clear();
	newoutlink.clear();
	return;	 
}















