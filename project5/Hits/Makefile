# Makefile

CC = g++
LD = g++

CFLAGS = -pedantic -Wall -c
HITS_LFLAGS = -ldl -lpthread

HITS_OBJS = Hits_main.o Hits.o

HITS = eecs485pa5h

default: $(HITS)

debug: CFLAGS += -g
debug: HITS_LFLAGS += -g
debug: $(HITS) 

$(HITS): $(HITS_OBJS)
	$(LD) $(HITS_LFLAGS) $(HITS_OBJS) -o $(HITS)

Hits_main.o: Hits_main.cpp Hits.h
	$(CC) $(CFLAGS) Hits_main.cpp

Hits.o: Hits.cpp Hits.h
	$(CC) $(CFLAGS) Hits.cpp

clean:
	rm -rf *.o $(HITS)
