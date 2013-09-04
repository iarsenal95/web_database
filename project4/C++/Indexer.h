#ifndef INDEXER_H
#define INDEXER_H

#include <iosfwd>

#include <vector>
using std::vector;

struct TermInfoInDoc{
	int doc_id;
	int tf;
	double norm;
};

struct TermInfo{
	double idf;
	int totalOccur;
	vector<TermInfoInDoc> info;
};

class Indexer {
public:
    void index(std::ifstream& content, std::ostream& outfile);
};



#endif
