#ifndef SNSERVEURUDP
#define SNSERVEURUDP

#include <string>
#include <iostream>


#ifdef __linux__
#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <unistd.h> // pour la fonction close()

#elif _WIN32
#include <winsock2.h>
#include <ws2tcpip.h>
#endif

using namespace std;

class SNServeurUDP
{
  private :
    unsigned short portEcoute;
    static int nbServeur;
    int numeroSocket;
    int etat;           // 0 : OK,  -1 : erreur socket(), -2 : erreur bind(), -3 erreur sendto()

    char adresseClient[20];
    unsigned short portClient;
    struct sockaddr_in dernierClient;
    socklen_t len;

    bool bloquant;



  public :
    SNServeurUDP(unsigned short unPortEcoute);
    ~SNServeurUDP();
    inline void ModifierBloquant(bool estBloquant) {bloquant = estBloquant;}
    bool EnvoyerMessage(const char message[], int longueurMessage);
    bool EnvoyerMessage(string message);
    int RecevoirMessage(char * message, int longueurMaxMessage);
    string RecevoirMessage();
    inline int Etat() {return etat;}
    inline const char * AdresseClient() const {return adresseClient;}
    inline unsigned short PortClient() const {return portClient;}
};

#endif
