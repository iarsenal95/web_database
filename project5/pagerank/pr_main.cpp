#include "pr.h"

using namespace std; 

pr mypr;

int main(int argc,  char **argv)
{
	if(argc != 6)
	{
		cerr<<"Incorrect number of commands"<<endl;
		exit(1);
	}
	//eecs485pa5p <dvalue> (-k <numiterations> | -converge <maxchange>) inputnetfile outputfile
	string temp;
	double dvalue = 0;
	string type;
	int numIt = -1;
	double maxchg = -1;
	string inputNet;
	string outputNet;
	
	// need some error checking here
	temp = argv[1];
	dvalue = atof(temp.c_str());
	type = argv[2];
	temp = argv[3];
	if(type == "-k")
		numIt = atoi(temp.c_str());
	else if(type =="-converge")
		maxchg = atof(temp.c_str());
	else
	{
		cerr<<"wrong argument for processing type"<<endl;
		exit(1);
	}
	inputNet = argv[4];
	outputNet = argv[5];
	
	//read inputs from file
	ifstream fin(inputNet.c_str()); 
	if(!fin.is_open())
	{
		cerr<<"open failed: "<<inputNet<<endl;
		exit(1);
	}
	ofstream fout(outputNet.c_str());
	if(!fout.is_open())
	{
		cerr<<"open failed: "<<outputNet<<endl;
		exit(1);
	}
	cout<<"PageRank running..."<<endl;
	
	mypr.init(fin);
	mypr.setDvalue(dvalue);
	if(type=="-k")
	{
		for(int i=0;i<numIt;i++)
		{
			cout<<"running iteration "<<i+1<<"...\t";
			mypr.process();
			cout<<"completed."<<endl;
		}
	}
	else
	{
		double chg;
		int i=0;
		while(1)
		{
			cout<<"running iteration "<<i+1<<"...\t";
			chg = mypr.process();
			cout<<"completed. max percentage change = "<<chg<<endl;
			if(chg <= maxchg)
				break;
			i++;
		}
	}
	mypr.printResult(fout);
	
	/*
	cout<<"test:"<<endl;
	cout<<"vertice: "<<mypr.getVertice()<<" arc: "<<mypr.getArc()<<endl;	
	// outLinks
	map<int, vector<int>* >::iterator it;
	for(it = mypr.outLinks.begin();it!=mypr.outLinks.end();it++)
	{
		cout<<"outLinks from "<<it->first<<": \t";
		for(int i=0;i<(*it->second).size();i++)
			cout<<(*it->second)[i]<<" ";
		cout<<endl;
	}
	cout<<endl;
	
	map<int, vector<int>* >::iterator it2;
	for(it2 = mypr.inLinks.begin();it2!=mypr.inLinks.end();it2++)
	{
		cout<<"inLinks to "<<it2->first<<": \t";
		for(int i=0;i<(*it2->second).size();i++)
		{
			cout<<(*it2->second)[i]<<" ";
		}
		cout<<endl;
	}
	*/
	cout<<"PageRank calculation finished."<<endl;
	fin.close();	
	fout.close();	
	return 0;
}
