#include "pr.h"

using namespace std;

void pr::init(ifstream& inputNet)
{
	cout<<"Reading input file...\t";
	string line;
	getline(inputNet, line);
	int found;
	found = line.find(' ');
	vertice = atoi( (line.substr(found+1)).c_str() );
	//read all vertices
	double average = 1/(double)vertice;// initial value for each page
	for(int i=0;i<vertice;i++)
	{
		if(!getline(inputNet, line))
		{
			cerr<<"not enough line in input file"<<endl;
			exit(1);
		}
		found = line.find(' ');
		int link;
		link = atoi((line.substr(0,found)).c_str());
		
		vector<int>* temp1 = new vector<int>;
		outLinks[link] = temp1;
		
		vector<int>* temp2 = new vector<int>;
		inLinks[link] = temp2;	
			
		value[link] = average;
	}
	
	//read all arcs
	getline(inputNet, line);
	found = line.find(' ');
	arc = atoi( (line.substr(found+1)).c_str() );
	for(int i=0;i<arc;i++)
	{
		if(!getline(inputNet, line))
		{
			cerr<<"not enough line in input file"<<endl;
			exit(1);
		}
		found = line.find(' ');
		int source = atoi((line.substr(0,found)).c_str());
		line = line.substr(found+1);
		found = line.find(' ');
		int target = atoi((line.substr(0,found)).c_str());
	
		if(source != target)//remove self edge not count
		{
			(*outLinks[source]).push_back(target);
			(*inLinks[target]).push_back(source);
		}
	}
	
	//check value for size of out going links
	map<int, vector<int>* >::iterator it;
	for(it = outLinks.begin();it != outLinks.end();it++)
	{
		outsize[it->first] = (*it->second).size();
		if(outsize[it->first]==0)
			zeroOut.push_back(it->first);		
	}
	cout<<"completed."<<endl;
	return;
}


double pr::process()
{
	value_pre = value;
	double sum;
	int linkA, linkN;
	double temp;
	double maxchg = 0, chg;
	int size;
	//double test=0;
	double vValue=0;//sum of virtual link value
	for(int i=0;i<zeroOut.size();i++)
	{
		vValue += value_pre[zeroOut[i]];
	}
	vValue = vValue/(double)vertice;
	map<int, double >::iterator it;
	for(it = value.begin();it != value.end();it++)
	{
		sum =0;
		linkA = it->first;
		for(int i=0;i<(*inLinks[linkA]).size();i++)
		{
			linkN = (*inLinks[linkA])[i];
			size = outsize[linkN];
			temp = value_pre[linkN]/size;
			sum += temp;
		}
		//add virtual links value
		sum += vValue;
		//assign pr value
		it->second = (1-dvalue)/vertice + dvalue*sum;
		
		chg = abs(it->second - value_pre[linkA])/value_pre[linkA];
		if(chg > maxchg)
			maxchg = chg;
			
		//test += it->second;
	}
	//cout<<"sum of page rank "<<test<<endl;
	return maxchg;
}

void pr::printResult(ofstream& outputNet)
{
	map<int, double >::iterator it;
	outputNet.precision(16);
	for(it = value.begin();it != value.end();it++)
		outputNet << it->first << " " << it->second << endl;
}

void pr::setDvalue(double d)
{
	dvalue = d;
	return;
}

int pr::getVertice()
{
	return vertice;
}

int pr::getArc()
{
	return arc;
}
