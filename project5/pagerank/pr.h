#ifndef PR_H
#define PR_H

#include <stdint.h>
#include <string>
#include <vector>
#include <map>
#include <cassert>
#include <cstdlib>
#include <cstring>
#include <fstream>
#include <iostream>
#include <sstream>
#include <cmath>
#include <functional>
#include <utility>
#include <queue>

using namespace std;

class pr{

private:
	int vertice;
	int arc;
	double dvalue;
	map<int, vector<int>* > outLinks;
	map<int, vector<int>* > inLinks;
	map<int, double > value;
	map<int, double > value_pre;
	map<int, int>outsize;
	vector <int> zeroOut;
public:	
	void init(ifstream& inputNet);
	double process();//return the percentage of change
	void setDvalue(double d);
	int getVertice();
	int getArc();
	void printResult(ofstream& outputNet);

};


#endif
