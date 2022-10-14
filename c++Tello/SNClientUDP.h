#ifndef SNCLIENTUDP
#define SNCLIENTUDP

#include <string>
using namespace std;

class SNClientUDP
{
  private :
    char adresseIPServeur[16];
    unsigned short portServeur;
    static int nbClient;
    int numeroSocket;
    int etat;           // 0 : OK,  -1 : erreur socket(), -2 : erreur bind(), -3 erreur sendto()

  public :
    SNClientUDP(unsigned short portFixe = 0);
    ~SNClientUDP();
    void ModifierAdresseIPServeur(const char adresseIP[]);
    void ModifierPortServeur(unsigned short port);
    bool EnvoyerMessage(const char * message, int longueurMessage);
    bool EnvoyerMessage(string message);
    int RecevoirMessage(char * message, int longueurMaxMessage);
    string RecevoirMessage();
    inline int Etat() {return etat;}
};

#endif
