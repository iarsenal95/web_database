#include "Hits.h"

#include <cstdlib>
#include <fstream>
#include <iostream>

using std::cout;
using std::cerr;
using std::endl;
using std::ifstream;
using std::string;
using std::ofstream;

Hits hits;

int main(int argc, char *argv[])
{
    if (argc < 8) {
        cerr << "Usage: eecs485pa5h <h value> (-k <numiterations>| -converge <maxchange>) \"queries\" <input-net-file> <input-inverted-index-file> <output-file>" << endl;
        return -1;
    }

    int h_value = atoi(argv[1]);
    if ( h_value < 1) {
        cerr << "h value must be positive integer." << endl;
        return -1;
    }
    
    string stopCrit = argv[2];
	if (stopCrit != "-k" && stopCrit != "-converge"){
		cerr << "stop criteria must be -k or -converge." << endl;
		return -1;
	}
	// check whether the input is valid
    double stopPara = atof(argv[3]);
    string query = argv[4];
    
    const char *fname_0 = argv[5];
    ifstream inputNet(fname_0);
    if (!inputNet.is_open()) {
        cerr << "Error opening file: " << fname_0 << endl;
        return -1;
    }
    
    const char *fname_1 = argv[6];
    ifstream inputIndex(fname_1);
    if (!inputIndex.is_open()) {
        cerr << "Error opening file: " << fname_1 << endl;
        return -1;
    }
    
    const char *fname_2 = argv[7];
    ofstream outfile(fname_2);
    if (!outfile.is_open()) {
        cerr << "Error opening file: " << fname_2 << endl;
        return -1;
    }
    cout << "Start web analysis with Hits algorithm:" << endl;
    hits.init(inputNet, inputIndex);
    hits.process_query(h_value, stopCrit, stopPara, query, outfile);
	outfile.close();
	inputNet.close();
	inputIndex.close();
    return 0;
}
