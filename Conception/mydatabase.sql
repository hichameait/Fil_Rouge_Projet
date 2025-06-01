/*==============================================================*/
/* Nom de SGBD :  SAP SQL Anywhere 17                           */
/* Date de création :  5/30/2025 9:57:16 AM                     */
/*==============================================================*/


if exists(select 1 from sys.sysforeignkey where role='FK_ABONNEME_ABONNEMEN_ABONNEME') then
    alter table ABONNEMENT
       delete foreign key FK_ABONNEME_ABONNEMEN_ABONNEME
end if;

if exists(select 1 from sys.sysforeignkey where role='FK_ABONNEME_ABONNEMEN_DENTISTE') then
    alter table ABONNEMENT
       delete foreign key FK_ABONNEME_ABONNEMEN_DENTISTE
end if;

if exists(select 1 from sys.sysforeignkey where role='FK_DOCUMENT_AJOUTE_DO_DENTISTE') then
    alter table DOCUMENTS
       delete foreign key FK_DOCUMENT_AJOUTE_DO_DENTISTE
end if;

if exists(select 1 from sys.sysforeignkey where role='FK_PREND_RD_PREND_RDV_SERVICE') then
    alter table PREND_RDV
       delete foreign key FK_PREND_RD_PREND_RDV_SERVICE
end if;

if exists(select 1 from sys.sysforeignkey where role='FK_PREND_RD_PREND_RDV_CLIENTS') then
    alter table PREND_RDV
       delete foreign key FK_PREND_RD_PREND_RDV_CLIENTS
end if;

if exists(select 1 from sys.sysforeignkey where role='FK_PROPOSE__PROPOSE_S_DENTISTE') then
    alter table PROPOSE_SERVICES
       delete foreign key FK_PROPOSE__PROPOSE_S_DENTISTE
end if;

if exists(select 1 from sys.sysforeignkey where role='FK_PROPOSE__PROPOSE_S_SERVICE') then
    alter table PROPOSE_SERVICES
       delete foreign key FK_PROPOSE__PROPOSE_S_SERVICE
end if;

drop index if exists ABONNEMENT.ABONNEMENT_FK;

drop index if exists ABONNEMENT.ABONNEMENT2_FK;

drop index if exists ABONNEMENT.ABONNEMENT_PK;

drop table if exists ABONNEMENT;

drop index if exists ABONNEMENTS.ABONNEMENTS_PK;

drop table if exists ABONNEMENTS;

drop index if exists CLIENTS.CLIENTS_PK;

drop table if exists CLIENTS;

drop index if exists DENTISTES.DENTISTES_PK;

drop table if exists DENTISTES;

drop index if exists DOCUMENTS.AJOUTE_DOCUMENT_FK;

drop index if exists DOCUMENTS.DOCUMENTS_PK;

drop table if exists DOCUMENTS;

drop index if exists PREND_RDV.PREND_RDV_FK;

drop index if exists PREND_RDV.PREND_RDV2_FK;

drop index if exists PREND_RDV.PREND_RDV_PK;

drop table if exists PREND_RDV;

drop index if exists PROPOSE_SERVICES.PROPOSE_SERVICES_FK;

drop index if exists PROPOSE_SERVICES.PROPOSE_SERVICES2_FK;

drop index if exists PROPOSE_SERVICES.PROPOSE_SERVICES_PK;

drop table if exists PROPOSE_SERVICES;

drop index if exists SERVICE.SERVICE_PK;

drop table if exists SERVICE;

/*==============================================================*/
/* Table : ABONNEMENT                                           */
/*==============================================================*/
create or replace table ABONNEMENT 
(
   ID_DENTISTE          integer                        not null,
   ID_ABONNEMENT        integer                        not null,
   constraint PK_ABONNEMENT primary key clustered (ID_DENTISTE, ID_ABONNEMENT)
);

/*==============================================================*/
/* Index : ABONNEMENT_PK                                        */
/*==============================================================*/
create unique clustered index ABONNEMENT_PK on ABONNEMENT (
ID_DENTISTE ASC,
ID_ABONNEMENT ASC
);

/*==============================================================*/
/* Index : ABONNEMENT2_FK                                       */
/*==============================================================*/
create index ABONNEMENT2_FK on ABONNEMENT (
ID_DENTISTE ASC
);

/*==============================================================*/
/* Index : ABONNEMENT_FK                                        */
/*==============================================================*/
create index ABONNEMENT_FK on ABONNEMENT (
ID_ABONNEMENT ASC
);

/*==============================================================*/
/* Table : ABONNEMENTS                                          */
/*==============================================================*/
create or replace table ABONNEMENTS 
(
   ID_ABONNEMENT        integer                        not null,
   PLAN                 long varchar                   not null,
   MONTANT              decimal                        null,
   METHODE_PAIEMENT     long varchar                   not null,
   STATUT_PAIEMENT      long varchar                   not null,
   DATE_CREATION        timestamp                      null,
   constraint PK_ABONNEMENTS primary key clustered (ID_ABONNEMENT)
);

/*==============================================================*/
/* Index : ABONNEMENTS_PK                                       */
/*==============================================================*/
create unique clustered index ABONNEMENTS_PK on ABONNEMENTS (
ID_ABONNEMENT ASC
);

/*==============================================================*/
/* Table : CLIENTS                                              */
/*==============================================================*/
create or replace table CLIENTS 
(
   ID_CLIENT            integer                        not null,
   NOM                  long varchar                   null,
   PRENOM               long varchar                   null,
   EMAIL                long varchar                   null,
   TELEPHONE            long varchar                   null,
   constraint PK_CLIENTS primary key clustered (ID_CLIENT)
);

/*==============================================================*/
/* Index : CLIENTS_PK                                           */
/*==============================================================*/
create unique clustered index CLIENTS_PK on CLIENTS (
ID_CLIENT ASC
);

/*==============================================================*/
/* Table : DENTISTES                                            */
/*==============================================================*/
create or replace table DENTISTES 
(
   ID_DENTISTE          integer                        not null,
   PRENOM               long varchar                   not null,
   NOM                  long varchar                   null,
   EMAIL                long varchar                   not null,
   MOT_DE_PASS          long varchar                   not null,
   NOM_UTILISA          long varchar                   null,
   ADDRESS              long varchar                   null,
   VILLE                long varchar                   not null,
   CODE_POSTAL          long varchar                   null,
   EMAIL_VERIFIE        smallint                       not null,
   constraint PK_DENTISTES primary key clustered (ID_DENTISTE)
);

/*==============================================================*/
/* Index : DENTISTES_PK                                         */
/*==============================================================*/
create unique clustered index DENTISTES_PK on DENTISTES (
ID_DENTISTE ASC
);

/*==============================================================*/
/* Table : DOCUMENTS                                            */
/*==============================================================*/
create or replace table DOCUMENTS 
(
   ID_DOCUMENT          integer                        not null,
   ID_DENTISTE          integer                        not null,
   NOM_FICHIER          long varchar                   not null,
   TYPE_DOCUMENT        long varchar                   not null,
   NOTES                long varchar                   null,
   DATE_AJOUT           timestamp                      not null,
   constraint PK_DOCUMENTS primary key clustered (ID_DOCUMENT)
);

/*==============================================================*/
/* Index : DOCUMENTS_PK                                         */
/*==============================================================*/
create unique clustered index DOCUMENTS_PK on DOCUMENTS (
ID_DOCUMENT ASC
);

/*==============================================================*/
/* Index : AJOUTE_DOCUMENT_FK                                   */
/*==============================================================*/
create index AJOUTE_DOCUMENT_FK on DOCUMENTS (
ID_DENTISTE ASC
);

/*==============================================================*/
/* Table : PREND_RDV                                            */
/*==============================================================*/
create or replace table PREND_RDV 
(
   ID_CLIENT            integer                        not null,
   ID_SERVICE           integer                        not null,
   DATE_RDV             date                           null,
   STATUS               smallint                       null,
   MONTANT              decimal                        null,
   METHOD_PAIEMENT      long varchar                   null,
   NOTE                 long varchar                   null,
   constraint PK_PREND_RDV primary key clustered (ID_CLIENT, ID_SERVICE)
);

/*==============================================================*/
/* Index : PREND_RDV_PK                                         */
/*==============================================================*/
create unique clustered index PREND_RDV_PK on PREND_RDV (
ID_CLIENT ASC,
ID_SERVICE ASC
);

/*==============================================================*/
/* Index : PREND_RDV2_FK                                        */
/*==============================================================*/
create index PREND_RDV2_FK on PREND_RDV (
ID_CLIENT ASC
);

/*==============================================================*/
/* Index : PREND_RDV_FK                                         */
/*==============================================================*/
create index PREND_RDV_FK on PREND_RDV (
ID_SERVICE ASC
);

/*==============================================================*/
/* Table : PROPOSE_SERVICES                                     */
/*==============================================================*/
create or replace table PROPOSE_SERVICES 
(
   ID_SERVICE           integer                        not null,
   ID_DENTISTE          integer                        not null,
   PRIX_SERVICE         decimal                        null,
   constraint PK_PROPOSE_SERVICES primary key clustered (ID_SERVICE, ID_DENTISTE)
);

/*==============================================================*/
/* Index : PROPOSE_SERVICES_PK                                  */
/*==============================================================*/
create unique clustered index PROPOSE_SERVICES_PK on PROPOSE_SERVICES (
ID_SERVICE ASC,
ID_DENTISTE ASC
);

/*==============================================================*/
/* Index : PROPOSE_SERVICES2_FK                                 */
/*==============================================================*/
create index PROPOSE_SERVICES2_FK on PROPOSE_SERVICES (
ID_SERVICE ASC
);

/*==============================================================*/
/* Index : PROPOSE_SERVICES_FK                                  */
/*==============================================================*/
create index PROPOSE_SERVICES_FK on PROPOSE_SERVICES (
ID_DENTISTE ASC
);

/*==============================================================*/
/* Table : SERVICE                                              */
/*==============================================================*/
create or replace table SERVICE 
(
   ID_SERVICE           integer                        not null,
   SERVICE              long varchar                   not null,
   constraint PK_SERVICE primary key clustered (ID_SERVICE)
);

/*==============================================================*/
/* Index : SERVICE_PK                                           */
/*==============================================================*/
create unique clustered index SERVICE_PK on SERVICE (
ID_SERVICE ASC
);

alter table ABONNEMENT
   add constraint FK_ABONNEME_ABONNEMEN_ABONNEME foreign key (ID_ABONNEMENT)
      references ABONNEMENTS (ID_ABONNEMENT)
      on update restrict
      on delete restrict;

alter table ABONNEMENT
   add constraint FK_ABONNEME_ABONNEMEN_DENTISTE foreign key (ID_DENTISTE)
      references DENTISTES (ID_DENTISTE)
      on update restrict
      on delete restrict;

alter table DOCUMENTS
   add constraint FK_DOCUMENT_AJOUTE_DO_DENTISTE foreign key (ID_DENTISTE)
      references DENTISTES (ID_DENTISTE)
      on update restrict
      on delete restrict;

alter table PREND_RDV
   add constraint FK_PREND_RD_PREND_RDV_SERVICE foreign key (ID_SERVICE)
      references SERVICE (ID_SERVICE)
      on update restrict
      on delete restrict;

alter table PREND_RDV
   add constraint FK_PREND_RD_PREND_RDV_CLIENTS foreign key (ID_CLIENT)
      references CLIENTS (ID_CLIENT)
      on update restrict
      on delete restrict;

alter table PROPOSE_SERVICES
   add constraint FK_PROPOSE__PROPOSE_S_DENTISTE foreign key (ID_DENTISTE)
      references DENTISTES (ID_DENTISTE)
      on update restrict
      on delete restrict;

alter table PROPOSE_SERVICES
   add constraint FK_PROPOSE__PROPOSE_S_SERVICE foreign key (ID_SERVICE)
      references SERVICE (ID_SERVICE)
      on update restrict
      on delete restrict;

