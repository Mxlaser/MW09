#include "SNClientUDP.h"

#include <iostream>
#include <string.h>

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

int SNClientUDP::nbClient = 0;

SNClientUDP::SNClientUDP( unsigned short portFixe)
{
  ModifierAdresseIPServeur(adresseIPServeur);
  ModifierPortServeur(portServeur);
  etat = 0;
  if(nbClient == 0)
  {
    #ifdef _WIN32
      WSADATA wsaData;
      int iResult;
      iResult = WSAStartup(MAKEWORD(2,2), &wsaData);
      if(iResult != 0)
      {
        cout << "Echec lors de l'initialisation de la DDL Winsock2." << endl;
        etat = -1;
      }
    #endif
  }
  nbClient ++;
  // Création de la socket de communication
  if ( (numeroSocket = socket(AF_INET, SOCK_DGRAM, IPPROTO_UDP)) < 0 ) {
    etat = -2;
  }
  cout << "Numero de la socket : " << numeroSocket << endl;
  if(portFixe > 0)
  {
    cout << "Un bind..." << endl;
    struct sockaddr_in client;
    client.sin_family    = AF_INET; // IPv4
    client.sin_addr.s_addr = htonl(INADDR_ANY); // Toutes les addresses IP dispo
    client.sin_port = htons(portFixe);

    if ( bind( numeroSocket, (struct sockaddr *)&client, sizeof(client)) == -1 )
    {
      etat = -2;
    }
  }
}


SNClientUDP::~SNClientUDP()
{
  #ifdef __linux__
  close(numeroSocket);
  #elif _WIN32
  closesocket(numeroSocket);
  WSACleanup();
  #endif
}

void SNClientUDP::ModifierAdresseIPServeur(const char adresseIP[])
{
  strcpy(this->adresseIPServeur, adresseIP);
}

void SNClientUDP::ModifierPortServeur(unsigned short port)
{
  this->portServeur = port;
}

bool SNClientUDP::EnvoyerMessage(const char * message, int longueurMessage)
{
  struct sockaddr_in serveur;
  serveur.sin_family    = AF_INET; // IPv4
  serveur.sin_addr.s_addr = inet_addr(this->adresseIPServeur);
  serveur.sin_port = htons(this->portServeur);

  int nbOctetsEmis = sendto(numeroSocket, message, longueurMessage, 0, (const struct sockaddr *) &serveur, sizeof(serveur));
  cout << "Nb octets emis : " << nbOctetsEmis << endl;
  if(nbOctetsEmis != longueurMessage)
  {
    etat = -3;
    return false;
  }
  return true;
}

bool SNClientUDP::EnvoyerMessage(string message)
{
  return EnvoyerMessage(message.c_str(), message.length());
}

int SNClientUDP::RecevoirMessage(char * message, int longueurMaxMessage)
{
  struct sockaddr_in serveur;
  socklen_t len = sizeof(serveur);
  int n = recvfrom(numeroSocket, message, longueurMaxMessage, 0, ( struct sockaddr *) &serveur, &len);
  return n;
}

string SNClientUDP::RecevoirMessage()
{
  //struct sockaddr_in serveur;
  char * message = new char[1501];
  //socklen_t len = sizeof(serveur);
  int n = RecevoirMessage(message, 1500);
  //n = recvfrom(numeroSocket, message, 1500, 0, ( struct sockaddr *) &serveur, &len);
  //cout << "N : " << n << endl;
  if(n>0) message[n] = '\0'; // Ajout du caratère nul en fin de chaine
  else message[0] = '\0';
  string str(message);
  delete[] message;
  return str;
}
