#ifndef INDEX_SERVER_H
#define INDEX_SERVER_H

#include <iosfwd>
#include <stdint.h>
#include <string>
#include <vector>
#include <map>

using std::map;
using std::vector;
using std::string;

struct Query_hit {
    Query_hit(const char *id_, double score_)
        : id(id_), score(score_)
        {}

    const char *id;
    double score;
};

struct TermInfoInDoc{
	const char* doc_id;
	int tf;
	double norm;
};

struct TermInfo{
	double idf;
	int totalOccur;
	vector<TermInfoInDoc> info;
};

class Index_server {
public:
    void run(int port);

    // Methods that students must implement.
    void init(std::ifstream& infile);
    void process_query(const std::string& query, std::vector<Query_hit>& hits);

private:
	map<string, TermInfo> invertedIndex;
	double score(double* query_weight, double* weight, int len);
};

#endif
