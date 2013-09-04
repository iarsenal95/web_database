#ifndef HITS_H
#define HITS_H

#include <stdint.h>
#include <string>
#include <vector>
#include <map>
#include <iostream>
#include <fstream>
using std::map;
using std::vector;
using std::string;

class Hits{
public:
    void init(std::ifstream& inputNet, std::ifstream& inputIndex); //Read in web from inputNet, save the web in outLinks and inLinks and build the index from inputIndex
    void process_query(int h_value, string stopCrit, double stopPara, string query, std::ostream& outfile);  // process the query and save the output in outfile 

private:
    map<int, int> baseSet;	// baseSet of the given query
	map<int, vector<int>* > outLinks; 
	map<int, vector<int>* > inLinks;
	map<string, vector<int>* > index;
	map<int, vector<int>* > newinlink;	// subweb of pages in baseSet
	map<int, vector<int>* > newoutlink;  // subweb of pages in baseSet
	void getBaseSet(string query, int h_value); // get the base set and save the pageid in map<int, int> baseSet
    void getHits(double* hub, double* auth, int* baseMap, int len);
	void getHitsConv(double* hub, double* auth, int* baseMap, int len, double stopPara);
	void baseSetHelper(vector<int>* v, vector<int>::iterator it, int num, int docid, bool isOut);
	
};

#endif
