/*
this is a simple checking code for general case (compare only)
*/

#include <cstdio>
#include <cstdlib>
#include <cstring>
#include <cmath>
#include <iostream>
#include <algorithm>
#include <set>
#include <map>
#include <vector>
#include <queue>
#include <stack>
#include <list>
#include <string>
#include <fstream>

using namespace std;

fstream input_file, key_file, ans_file;
string key, ans;

void correct(){
	cout << "$#1";
	exit(0);
}

void wrong(){
	cout << "$#0";
	exit(0);
}

void prepareData(){
	cin >> key;
	cin >> ans;
	key_file.open(key.c_str());
	ans_file.open(ans.c_str());
}

vector<string> key_arr, ans_arr;

int main()
{
	prepareData();

	while(not key_file.eof())
	{
		key_file >> key;
		key_arr.push_back(key);
	}

	while(not ans_file.eof())
	{
		ans_file >> ans;
		ans_arr.push_back(ans);
	}

	if(ans_arr.size() == key_arr.size())
	{
		int sz = ans_arr.size();
		for(int i = 0; i < sz; i++) 
		{
			if(ans_arr[i] != key_arr[i]) 
			{
				wrong();
			}
		}
		correct();
	}

	wrong();

	return 0;
}