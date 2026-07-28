-- =========================================================
-- CRIAÇÃO DO BANCO DE DADOS
-- =========================================================
CREATE DATABASE DB_PGE;
USE DB_PGE;

-- =========================================================
-- TABELA: USUARIOS
-- Armazena todos os usuários do sistema
-- (administrador, secretaria, professor, etc.)
-- =========================================================
CREATE TABLE USUARIOS (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    NOME VARCHAR(100) NOT NULL,
    EMAIL VARCHAR(100) NOT NULL UNIQUE,
    SENHA VARCHAR(255) NOT NULL,
    PERFIL VARCHAR(50) NOT NULL,
    STATUS VARCHAR(20) DEFAULT 'ATIVO'
);

-- =========================================================
-- TABELA: ALUNOS
-- Armazena os dados dos alunos cadastrados
-- =========================================================
CREATE TABLE ALUNOS (
    ID              INT AUTO_INCREMENT PRIMARY KEY,
    NOME            VARCHAR(100) NOT NULL,
    DATA_NASCIMENTO DATE,
    CPF             VARCHAR(20),
    EMAIL           VARCHAR(100),
    TELEFONE        VARCHAR(20),
    STATUS          VARCHAR(20) DEFAULT 'ATIVO'
);

-- =========================================================
-- TABELA: CURSOSz
-- Cadastro dos cursos disponíveis
-- =========================================================
CREATE TABLE CURSOS (
    ID              INT AUTO_INCREMENT PRIMARY KEY,
    NOME            VARCHAR(100) NOT NULL,
    DESCRICAO       VARCHAR(255),
    CARGA_HORARIA   INT,
    STATUS          VARCHAR(20)  DEFAULT 'ATIVO'
);

-- =========================================================
-- TABELA: SALAS
-- Representa as salas físicas onde ocorrem as aulas
-- =========================================================
CREATE TABLE SALAS (
    ID          INT AUTO_INCREMENT PRIMARY KEY,
    NOME        VARCHAR(50) NOT NULL,
    BLOCO       VARCHAR(50),
    CAPACIDADE  INT
);

-- =========================================================
-- TABELA: MATRICULAS
-- Relaciona ALUNOS e CURSOS (N:N)
-- Um aluno pode fazer vários cursos
-- Um curso pode ter vários alunos
-- =========================================================
CREATE TABLE MATRICULAS (
    ID              INT AUTO_INCREMENT PRIMARY KEY,
    ALUNO_ID        INT NOT NULL,
    CURSO_ID        INT NOT NULL,
    DATA_MATRICULA  DATE,
    STATUS          VARCHAR(20) DEFAULT 'ATIVA',

    -- ============================
    -- CHAVES ESTRANGEIRAS (FOREIGN KEYS)
    -- ============================

    -- Relaciona com a tabela ALUNOS
    CONSTRAINT FK_MATRICULA_ALUNO
        FOREIGN KEY (ALUNO_ID)
        REFERENCES ALUNOS(ID)
        ON DELETE CASCADE   -- Se o aluno for excluído, remove matrícula
        ON UPDATE CASCADE,

    -- Relaciona com a tabela CURSOS
    CONSTRAINT FK_MATRICULA_CURSO
        FOREIGN KEY (CURSO_ID)
        REFERENCES CURSOS(ID)
        ON DELETE CASCADE   -- Se o curso for excluído, remove matrícula
        ON UPDATE CASCADE,

    -- Evita que o mesmo aluno se matricule duas vezes no mesmo curso
    CONSTRAINT UNIQUE_MATRICULA UNIQUE (ALUNO_ID, CURSO_ID)
);

-- =========================================================
-- TABELA: CHAMADAS
-- Representa uma aula em uma data específica
-- =========================================================
CREATE TABLE CHAMADAS (
    ID          INT AUTO_INCREMENT PRIMARY KEY,
    CURSO_ID    INT NOT NULL,
    SALA_ID     INT NOT NULL,
    DATA        DATE NOT NULL,
    CONTEUDO    VARCHAR(255),

    -- ============================
    -- CHAVES ESTRANGEIRAS
    -- ============================

    -- Relaciona com CURSOS
    CONSTRAINT FK_CHAMADA_CURSO
        FOREIGN KEY (CURSO_ID)
        REFERENCES CURSOS(ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    -- Relaciona com SALAS
    CONSTRAINT FK_CHAMADA_SALA
        FOREIGN KEY (SALA_ID)
        REFERENCES SALAS(ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


-- =========================================================
-- TABELA: PRESENCAS
-- Controla a presença dos alunos nas aulas (chamadas)
-- =========================================================
CREATE TABLE PRESENCAS (
    ID          INT AUTO_INCREMENT PRIMARY KEY,
    CHAMADA_ID  INT NOT NULL,
    ALUNO_ID    INT NOT NULL,
    PRESENTE    BOOLEAN NOT NULL,

    -- ============================
    -- CHAVES ESTRANGEIRAS
    -- ============================

    -- Relaciona com CHAMADAS
    CONSTRAINT FK_PRESENCA_CHAMADA
        FOREIGN KEY (CHAMADA_ID)
        REFERENCES CHAMADAS(ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    -- Relaciona com ALUNOS
    CONSTRAINT FK_PRESENCA_ALUNO
        FOREIGN KEY (ALUNO_ID)
        REFERENCES ALUNOS(ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    -- Evita duplicidade de presença na mesma aula
    CONSTRAINT UNIQUE_PRESENCA UNIQUE (CHAMADA_ID, ALUNO_ID)
);