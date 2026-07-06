# Hospital Management System - Security & Privacy Refactoring

Questo repository contiene il progetto di re-engineering e bonifica del sistema gestionale ospedaliero **Hospital Management System (HMS)**, sviluppato nell'ambito del corso di **Secure Software Engineering** (A.A. 2025-2026).

## 👥 Team di Sviluppato
*   **Francesco Caterina** - f.caterina1@studenti.uniba.it
*   **Alessia Lopetuso** - a.lopetuso3@studenti.uniba.it

**Università degli Studi di Bari Aldo Moro**  
*Dipartimento di Informatica - SERLAB (Software Engineering Research Laboratory)*

---

## 📝 Descrizione del Progetto
Il progetto ha l'obiettivo di condurre un security & privacy assessment completo su un'applicazione web legacy sviluppata in **PHP** e **MySQL**, utilizzata per la gestione operativa di una struttura ospedaliera (autenticazione ruoli, prenotazione visite, prescrizioni mediche e fatturazione). 

L'attività si è articolata in tre macro-fasi:
1.  **Red Teaming & Penetration Testing:** Attività di ricognizione (passiva e attiva) e simulazione di attacchi reali per mappare la superficie di esposizione.
2.  **Vulnerabilities Analysis (SAST/DAST):** Analisi statica del codice sorgente tramite *Micro Focus Fortify SCA* e analisi dinamica automatizzata mediante *OWASP ZAP*.
3.  **Security Fix & Privacy Assessment:** Bonifica del codice sorgente (Code Hardening) e re-ingegnerizzazione dell'architettura applicativa secondo la metodologia **POSD** (Privacy Oriented Software Development), applicando le strategie di *Privacy by Design* della Privacy Knowledge Base (PKB) del SERLAB.

---

## 🔒 Mitigazioni e Fix Implementati

### 1. Ingegneria della Sicurezza (OWASP Top 10)
*   **A03:2021 – Injection:** Rimozione totale della concatenazione diretta delle stringhe nelle query SQL. Refactoring completo del backend (autenticazione, registrazione, inserimento e cancellazione appuntamenti) tramite l'adozione sistematica dei **Prepared Statements** (Query Parametrizzate) con estensione `mysqli`.
*   **A03:2021 – Cross-Site Scripting (XSS):** Messa in sicurezza dei flussi di andata (input) e di ritorno (output). Implementazione del meccanismo di **Output Encoding** contestuale tramite la funzione nativa `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` su tutti i record estratti dal database e stampati a schermo nelle GUI di pazienti e medici, neutralizzando XSS sia riflessi che persistenti.
*   **A05:2021 – Security Misconfiguration (Server Hardening):** 
    *   Disabilitazione dell'auto-indexing dei file nel server Apache tramite la direttiva `Options -Indexes` nel file `.htaccess`.
    *   Iniezione e forzatura degli header di sicurezza HTTP fondamentali (`X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff`, `CSP` e `X-XSS-Protection`) per proteggere i client da attacchi di Clickjacking e MIME-sniffing.
    *   Inibizione dell'accesso alla cartella di versioning nascosta `.git/HEAD` tramite regole di `DirectoryMatch` restrittive.

### 2. Privacy by Design & Architecture (Modello MVC)
L'architettura del sistema è stata riorganizzata logica secondo il pattern **MVC (Model-View-Controller)** per ospitare i componenti di privacy della *Privacy Knowledge Base* del corso:
*   **VIEW (GUI Panels):** Ospita i controlli di trasparenza del modulo *Inform/Control* (consenso esplicito e diritto all'oblio).
*   **CONTROLLER (Backend Logic):** Integra la strategia **MINIMISE** (*Strip Invisible Metadata* via Prepared Statements) e la logica di controllo autorizzativo per abilitare/disabilitare funzioni in modo sicuro (*Enable/Disable Functions* per la cancellazione degli appuntamenti - GDPR Art. 17).
*   **MODEL (Data Layer):** Implementa la strategia **SEPARATE** tramite la compartimentazione e il confinamento dei dati (*User data confinement*), dividendo il DB centralizzato originario in due istanze isolate (Authentication Data e Health Data). Integra la strategia **HIDE** applicando algoritmi di hashing sicuro (`bcrypt`) e stringhe di mascheramento (`********`) per occultare le credenziali all'interfaccia.
*   **FLUSSO DI RISPOSTA:** Presidiato dal modulo **ENFORCE** che applica l'*Obligation Management* (Output Encoding) su tutti i dati sanitari e anagrafici estratti prima di inviarli alla View.

---

## 🛠️ Stack Tecnologico
*   **Linguaggio di Backend:** PHP 8.0.0
*   **Database Layer:** MariaDB / MySQL
*   **Frontend Libraries:** Bootstrap, jQuery (aggiornate alle ultime versioni stabili)
*   **Tool di Security:** Micro Focus Fortify SCA (SAST), OWASP ZAP (DAST), SQLMap, Nikto, Nmap, Gobuster, Dirb.
*   **Librerie Core di Terze Parti:** TCPDF (per la generazione sicura delle fatture in PDF).
