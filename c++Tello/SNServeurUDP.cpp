#include "SNServeurUDP.h"
#include <string>
#include <string.h>
#include <iostream>

#include <errno.h>
/*
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
*/
using namespace std;



int SNServeurUDP::nbServeur = 0;

SNServeurUDP::SNServeurUDP( unsigned short unPortEcoute)
{
    portEcoute = unPortEcoute;
    etat = 0;
    bloquant = true;
    if(nbServeur == 0)
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
    nbServeur ++;
    // Création de la socket de communication
    if ( (numeroSocket = socket(AF_INET, SOCK_DGRAM, IPPROTO_UDP)) < 0 ) {
        etat = -2;
    }
    cout << "Numero de la socket : " << numeroSocket << endl;
    if(portEcoute > 0)
    {
        cout << "Un bind..." << endl;
        struct sockaddr_in serveur;
        serveur.sin_family    = AF_INET; // IPv4
        serveur.sin_addr.s_addr = htonl(INADDR_ANY); // Toutes les addresses IP dispo
        serveur.sin_port = htons(portEcoute);

        if ( bind( numeroSocket, (struct sockaddr *)&serveur, sizeof(serveur)) == -1 )
        {
            etat = -3;
            cout << "Echec du bind..." << endl;
        }
    }
}


SNServeurUDP::~SNServeurUDP()
{
    #ifdef __linux__
    close(numeroSocket);
    #elif _WIN32
    closesocket(numeroSocket);
    WSACleanup();
    #endif
}


bool SNServeurUDP::EnvoyerMessage(const char message[], int longueurMessage)
{


/*    cout << "MESSAGE A ENVOYER : " << message << " - NB CAR : " << longueurMessage << endl;
    cout << "NUM SOCKET : " << numeroSocket << endl;
    cout << "ADDR : " << adresseClient << ":" << portClient<<endl;
*/
/*    dernierClient.sin_family    = AF_INET; // IPv4
    dernierClient.sin_addr.s_addr = inet_addr(this->adresseClient);
    dernierClient.sin_port = htons(portClient);
*/
    cout << "ADRESSE CLIENT : " << inet_ntoa(dernierClient.sin_addr) << ":" << (unsigned short)ntohs(dernierClient.sin_port) << endl;
    int nbOctetsEmis = sendto(numeroSocket, message, longueurMessage, 0, (const struct sockaddr *) &dernierClient, sizeof(dernierClient));
//    cout << "Nb octets emis : " << nbOctetsEmis << endl;
    if(nbOctetsEmis != longueurMessage)
    {
        //cout << "Erreur lors de l'emission du message" << endl;
        //cout << errno << endl;
        etat = -3;
        return false;
    }
    return true;
}

bool SNServeurUDP::EnvoyerMessage(string message)
{
    return EnvoyerMessage(message.c_str(), message.length());
}

int SNServeurUDP::RecevoirMessage(char * message, int longueurMaxMessage)
{
    int n;
    //dernierClient.sin_family    = AF_INET; // IPv4
    len = sizeof(dernierClient);
    if(bloquant)
    {
        n = recvfrom(numeroSocket, message, longueurMaxMessage, 0, ( struct sockaddr *) &dernierClient, &len);
    }
    else
    {
        n = recvfrom(numeroSocket, message, longueurMaxMessage, MSG_DONTWAIT, ( struct sockaddr *) &dernierClient, &len);
    }
    if(n>0)
    {
        strcpy(adresseClient,inet_ntoa(dernierClient.sin_addr));
        portClient = (unsigned short)ntohs(dernierClient.sin_port);
        //cout << "MESSAGE RECU : " << string(message,n) << endl;
        //cout << "ADRESSE CLIENT : " << inet_ntoa(dernierClient.sin_addr) << ":" << (unsigned short)ntohs(dernierClient.sin_port) << endl;
        //cout << "ADDR : " << adresseClient << ":" << portClient<<endl;
    }
    return n;
}

string SNServeurUDP::RecevoirMessage()
{
    char * msg = new char[1501];
    int n = this->RecevoirMessage(msg, 1500);
    if(n>0)
    {
        msg[n] = '\0';
    }
    else
    {
        msg[0] = '\0';
    }
//    cout << "ADRESSE CLIENT : " << inet_ntoa(dernierClient.sin_addr) << ":" << (unsigned short)ntohs(dernierClient.sin_port) << endl;
//    cout << "ADDR : " << adresseClient << ":" << portClient<<endl;
    string str(msg);
    delete[] msg;
    return str;
}
