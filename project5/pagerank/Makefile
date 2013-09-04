all: pr_main.o pr.o
	g++ pr_main.o pr.o -o eecs485pa5p
pr_main.o:
	g++ -c pr_main.cpp
pr.o:
	g++ -c pr.cpp
clean:
	rm -rf *.o eecs485pa5p
