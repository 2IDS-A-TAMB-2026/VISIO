-- drop database bd_visio;

CREATE DATABASE IF NOT EXISTS BD_VISIO;
USE BD_VISIO;

CREATE TABLE ADMIN (
    CNPJ VARCHAR(18) PRIMARY KEY,
    NOME VARCHAR(150) NOT NULL DEFAULT '',
    EMAIL VARCHAR(150) NOT NULL,
    FOTO VARCHAR(255) NOT NULL DEFAULT '',
    TELEFONE VARCHAR(20) NOT NULL,
    SENHA VARCHAR(255) NOT NULL
);

CREATE TABLE SENSOR (
    ID_SENSOR INT AUTO_INCREMENT PRIMARY KEY,
    NOME VARCHAR(100) NOT NULL,
    FOTO VARCHAR(255) NOT NULL DEFAULT '',
    DESCRICAO VARCHAR(255) NOT NULL,
    CIRCUITO VARCHAR(255) NOT NULL DEFAULT ''
);

CREATE TABLE PERGUNTA (
    ID_PERGUNTA INT AUTO_INCREMENT PRIMARY KEY,
    DESCRICAO VARCHAR(255) NOT NULL,
    NIVEL_DIFICULDADE VARCHAR(20) NOT NULL
);

CREATE TABLE ALTERNATIVA (
    ID_ALTERNATIVA INT AUTO_INCREMENT PRIMARY KEY,
    DESCRICAO VARCHAR(255) NOT NULL,
    IS_CORRETA TINYINT(1) NOT NULL DEFAULT 0,
    FK_ID_PERGUNTA INT NOT NULL,
    FOREIGN KEY (FK_ID_PERGUNTA)
        REFERENCES PERGUNTA (ID_PERGUNTA)
        ON DELETE CASCADE
);

CREATE TABLE USUARIO (
    CPF VARCHAR(14) PRIMARY KEY,
    NOME VARCHAR(150) NOT NULL DEFAULT '',
    EMAIL VARCHAR(150) NOT NULL UNIQUE,
    SENHA VARCHAR(255) NOT NULL,
    CARTAO VARCHAR(16) NOT NULL DEFAULT '' UNIQUE,
    DATA_NASCIMENTO DATE NOT NULL,
    TELEFONE VARCHAR(20) NOT NULL,
    FOTO VARCHAR(255) NOT NULL DEFAULT ''
);

CREATE TABLE RESPONDE (
    ID_RESPONDE INT AUTO_INCREMENT PRIMARY KEY,
    FK_CPF_USUARIO VARCHAR(14) NOT NULL,
    FK_ID_ALTERNATIVA INT NOT NULL,
    RESPONDIDO_EM DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (FK_CPF_USUARIO)
        REFERENCES USUARIO (CPF)
        ON DELETE CASCADE,
    FOREIGN KEY (FK_ID_ALTERNATIVA)
        REFERENCES ALTERNATIVA (ID_ALTERNATIVA)
        ON DELETE CASCADE
);

CREATE TABLE RESET_SENHA (
    ID_RESET INT AUTO_INCREMENT PRIMARY KEY,
    FK_EMAIL VARCHAR(150) NOT NULL,
    TOKEN VARCHAR(64) NOT NULL UNIQUE,
    USADO TINYINT(1) NOT NULL DEFAULT 0,
    CRIADO_EM DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    EXPIRA_EM DATETIME NOT NULL,
    FOREIGN KEY (FK_EMAIL)
        REFERENCES USUARIO (EMAIL)
        ON DELETE CASCADE
);

CREATE TABLE RESET_SENHA_ADMIN (
    ID_RESET INT AUTO_INCREMENT PRIMARY KEY,
    FK_CNPJ VARCHAR(18) NOT NULL,
    TOKEN VARCHAR(64) NOT NULL UNIQUE,
    USADO TINYINT(1) NOT NULL DEFAULT 0,
    CRIADO_EM DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    EXPIRA_EM DATETIME NOT NULL,
    FOREIGN KEY (FK_CNPJ)
        REFERENCES ADMIN (CNPJ)
        ON DELETE CASCADE
);

-- ===== ADMIN  =====
INSERT INTO ADMIN (CNPJ, NOME, EMAIL, TELEFONE, SENHA) VALUES
('23.456.789/0001-02','Matheus Neri','matheus@admin.com','(19) 99315-1477','$2a$12$RKMfDeHF4W7ngIWuDBm9buhlwMTYikgwTVkyskwDtOQxrVc5MfIni'), 
('34.567.890/0001-03','Emily Maiara','emily@admin.com','(19) 99123-4567','$2a$12$RKMfDeHF4W7ngIWuDBm9buhlwMTYikgwTVkyskwDtOQxrVc5MfIni'),
('45.678.901/0001-04','Fernanda Amaral','fernanda@admin.com','(19) 99234-5678','$2a$12$RKMfDeHF4W7ngIWuDBm9buhlwMTYikgwTVkyskwDtOQxrVc5MfIni'), 
('56.789.012/0001-05','Sophia Perom','sophia@admin.com','(19) 99345-6789','$2a$12$RKMfDeHF4W7ngIWuDBm9buhlwMTYikgwTVkyskwDtOQxrVc5MfIni'), 
('67.890.123/0001-06','Guilherme Staconi','guilherme@admin.com','(19) 99456-7890','$2a$12$RKMfDeHF4W7ngIWuDBm9buhlwMTYikgwTVkyskwDtOQxrVc5MfIni'), 
('78.901.234/0001-07','Isabela Tessarin','isabela@admin.com','(19) 99508-3585','$2a$12$RKMfDeHF4W7ngIWuDBm9buhlwMTYikgwTVkyskwDtOQxrVc5MfIni'), 
('89.012.345/0001-08','Lorrana Generoso','lorrana@admin.com','(19) 99890-8934','$2a$12$RKMfDeHF4W7ngIWuDBm9buhlwMTYikgwTVkyskwDtOQxrVc5MfIni'), 

('12.345.678/0001-01', 'Administrador VISIO', 'admin@visio.com', '(11) 98765-4321', '$2b$12$up2lvf2xBzAw6C6PVUIQW.IXCAviqwpyOGpEMNiSArmiQGgZO.kl.'), 
('90.123.456/0001-09','Rafael Augusto Barbosa','admin9@iotlab.com','(11) 98765-4329','$2b$10$7juJcZu9Wea1Q1BNyPnso.LkD/.lWOHSPKse.dvPYQXvvW/GvGJK.'), 
('11.234.567/0001-10','Patrícia Gomes Ribeiro','admin10@iotlab.com','(11) 98765-4330','$2b$10$n9MCzMeqsD967AzXKZDpVe.BceckwP4iTPsAQuClp.ilRVIDF0Neu'),
('12.345.678/0001-90','Lucas Gabriel Carvalho','admin11@iotlab.com','(11) 91234-5678','$2b$10$piyqIYdRmste6TEIwJE3IuT.shPJPHliySZ2/yIl6JJ34KRmAloIu'),
('98.765.432/0001-10','Camila Andrade Nascimento','admin12@iotlab.com','(21) 92345-6789','$2b$10$C0B/PNL/JAApdrcFqaAQie1pDNwlNmcziYXFidvWePiWR7noaOR2C'), 
('45.678.123/0001-55','Diego Fernandes Araújo','admin13@iotlab.com','(31) 93456-7890','$2b$10$QTLkde4oK6aUFZNpG5NvPu3fClrP2aKS5PNSZSlzrht8V.GTCxJPi'), 
('67.890.234/0001-21','Larissa Mendes Cardoso','admin14@iotlab.com','(41) 94567-8901','$2b$10$1S7CJyGtAfuM2z0ExDLqFeFEmSqidK2B3dwO/nDvzD2JuaXeFA/XW'), 
('23.456.789/0001-87','Thiago Vieira Rocha','admin15@iotlab.com','(51) 95678-9012','$2b$10$2nNarr6SGoM01LmLdXNIeudq1QQi5PUh/oUR17GKAv7Q5.K.hTHme'), 
('34.567.890/0001-66','Beatriz Correia Dias','admin16@iotlab.com','(61) 96789-0123','$2b$10$vEZvadvtGn4S9hhQ7GRDuuFelPgZ.yFeRqqiivj.X4qvgKciAAMdm'), 
('56.789.012/0001-44','Felipe Moreira Castro','admin17@iotlab.com','(71) 97890-1234','$2b$10$lBsY7I4KHWcmgj.NxwJcwuBSCoOA/S76OMpIubDTw7zzqrxG4zM9S'), 
('78.901.234/0001-32','Gabriela Teixeira Pinto','admin18@iotlab.com','(81) 98901-2345','$2b$10$dMpAq6EnP3U/gl41CnQXcO4EAtt/dX5gjDduYu8XlJFCIczsFd0Fm'),
('89.012.345/0001-11','Eduardo Henrique Ramos','admin19@iotlab.com','(91) 99123-3456','$2b$10$yYwCTNgV.rlNJx5yyngqEeZxTJpiCxhodvO.t1jjmiGCVsnKozoOa'), 
('90.123.456/0001-99','Vanessa Cunha Monteiro','admin20@iotlab.com','(19) 90234-4567','$2b$10$0Na9cHHsG9vpjHBMof1cx.u8m3C3gBGbGkreeef6uWXpRVZLqP0.q'), 
('11.222.333/0001-45','Marcelo Augusto Lopes','admin21@iotlab.com','(27) 91345-5678','$2b$10$eU4/qFPdEkuebPHbfAVBreynfDCphsQUMdIqd.6H1fu5k.tENTNrq'),
('22.333.444/0001-78','Aline Cristina Batista','admin22@iotlab.com','(48) 92456-6789','$2b$10$iuFC5UBWjbccpwYpGp23tOvmeTMYPdhQ.Svye.PWYFQpr1JQdKGOC'),
('33.444.555/0001-12','Rodrigo Nunes Freitas','admin23@iotlab.com','(85) 93567-7890','$2b$10$TwarGTvH0qHFzWPNGUiTQ.lyM.J8wivj8YYvfvTXXrN7Ygkd1Bogi'),
('44.555.666/0001-34','Tatiane Borges Cavalcante','admin24@iotlab.com','(92) 94678-8901','$2b$10$8TcnW2tXoDjK9bVC/e7RIuUT.OM8Jpow44rypuiMc1GlZ/xaG1LT2'), 
('55.666.777/0001-56','Leonardo Pires Tavares','admin25@iotlab.com','(98) 95789-9012','$2b$10$WXWRE20urHhtOowuW3LZUegzdh27E4Gp.aKHq5257WPPAap.BLX5a'), 
('66.777.888/0001-67','Renata Duarte Farias','admin26@iotlab.com','(62) 96890-0123','$2b$10$28oCTES/XlDQylbNp88YW.9aRPUgsIrGMOkKaKXP38WJt1NJDhmj.'), 
('77.888.999/0001-23','Vinicius Campos Moraes','admin27@iotlab.com','(64) 97901-1234','$2b$10$MZL.UkKVS3mDqUPixPnASeVa3AbpOCGLjkv8rxXZoHE6Nayihmuk6'), 
('88.999.000/0001-88','Priscila Azevedo Macedo','admin28@iotlab.com','(65) 98012-2345','$2b$10$M44VzS.j2/prr2CjKqJDweaWUYgJWqg92Hz6W4J5oSXlaKKm89jOa'), 
('99.000.111/0001-77','André Luiz Nogueira','admin29@iotlab.com','(67) 99123-4567','$2b$10$mmIfaimTs.sjYniXkL4BU.XiQui/v/JAskCD5MY8Q6rMyDfN6V4FW'), 
('10.101.202/0001-33','Cláudia Regina Pacheco','admin30@iotlab.com','(68) 90234-5678','$2b$10$Vn6yx.8szHGdbDzD9pFA8OGZHuYTM7.2cZjz23/.F839QCg1P4ST6'); 

-- ===== SENSOR  =====
INSERT INTO SENSOR (NOME, FOTO, DESCRICAO, CIRCUITO) VALUES
('Módulo de Sinalização Sonora', 'assets/images/Sensores/Módulo_de_Sinalização_Sonora.png', 'Módulo utilizado para emitir sinais sonoros, alarmes e avisos em projetos eletrônicos.', 'Arduino + Módulo de Sinalização Sonora'),
('Módulo de Comunicação Sem Fio', 'assets/images/Sensores/Módulo_de_Comunicação_Sem_Fio.png', 'Módulo responsável pela comunicação de dados sem fio entre dispositivos.', 'Arduino + Módulo de Comunicação Sem Fio + Antena'),
('Conversor de Nível Lógico', 'assets/images/Sensores/Conversor_de_Nível_Lógico.png', 'Dispositivo utilizado para converter sinais elétricos entre diferentes níveis de tensão, como 3,3V e 5V.', 'Arduino + Conversor de Nível Lógico + ESP-32'),
('ESP-32', 'assets/images/Sensores/ESP-32.png', 'Microcontrolador com conectividade Wi-Fi e Bluetooth integrado, amplamente utilizado em projetos de Internet das Coisas (IoT).', 'ESP-32 + Fonte 5V + Cabo USB'),
('Módulo de Relés', 'assets/images/Sensores/Módulo_de_Relés.png', 'Módulo utilizado para controlar dispositivos de maior potência por meio de sinais de baixa tensão.', 'Arduino + Módulo de Relés + Carga Elétrica'),
('Display de Cristal Líquido', 'assets/images/Sensores/Display_de_Cristal_Líquido.png', 'Display utilizado para exibir textos, números e informações em projetos eletrônicos.', 'Arduino + Display LCD 16x2 + Potenciômetro 10k'),
('Sensor de Identificação por Radiofrequência', 'assets/images/Sensores/Sensor_de_Identificação_por_Radiofrequência.png', 'Sensor utilizado para leitura e identificação de cartões e etiquetas RFID.', 'Arduino + RC522 + Tag RFID'),
('Sensor de Distância', 'assets/images/Sensores/Sensor_de_Distância.png', 'Sensor capaz de medir a distância entre ele e um objeto utilizando ondas ultrassônicas.', 'Arduino + HC-SR04 + Jumpers'),
('Sensor de Gás', 'assets/images/Sensores/Sensor_de_Gás.png', 'Sensor utilizado para detectar a presença e concentração de gases no ambiente.', 'Arduino + MQ-2 + Resistor 10k'),
('Sensor de Movimento e Orientação', 'assets/images/Sensores/Sensor_de_Movimento_e_Orientação.png', 'Sensor que detecta aceleração, inclinação e rotação, sendo utilizado em aplicações de movimento.', 'Arduino + MPU6050 + Jumpers'),
('Sensor de Umidade e Temperatura', 'assets/images/Sensores/Sensor_de_Umidade_e_Temperatura.png', 'Sensor utilizado para medir a temperatura e a umidade relativa do ar.', 'Arduino + DHT11 + Resistor 10k');

-- ===== PERGUNTA =====
INSERT INTO PERGUNTA (DESCRICAO, NIVEL_DIFICULDADE) VALUES
('O que mede um sensor LDR?','Fácil'),
('Para que serve o sensor PIR?','Fácil'),
('O que é IoT?','Médio'),
('Como funciona um sensor ultrassônico?','Médio'),
('O que significa GPIO?','Médio'),
('Qual a função do sensor MQ-2?','Médio'),
('O que é PWM?','Difícil'),
('Como funciona um ESP32?','Difícil'),
('O que é protocolo MQTT?','Difícil'),
('Diferença entre Arduino e Raspberry Pi?','Difícil'),
('O que é um sensor DHT11?','Fácil'),
('O que mede um sensor de temperatura?','Fácil'),
('O que é um atuador?','Fácil'),
('Para que serve um relé?','Fácil'),
('O que é corrente elétrica?','Fácil'),
('O que é um microcontrolador?','Médio'),
('O que é um circuito integrado?','Médio'),
('Como funciona um sensor de gás?','Médio'),
('O que é comunicação serial?','Médio'),
('O que é I2C?','Médio'),
('O que é SPI?','Médio'),
('O que é tensão elétrica?','Médio'),
('O que é ADC (Conversor Analógico-Digital)?','Difícil'),
('O que é DAC (Conversor Digital-Analógico)?','Difícil'),
('O que é interrupção em microcontroladores?','Difícil'),
('O que é debounce em botões?','Difícil'),
('O que é consumo de corrente em stand-by?','Difícil'),
('O que é watchdog timer?','Difícil'),
('O que é overclock?','Difícil'),
('O que é firmware?','Difícil');

-- ===== ALTERNATIVA (4 por pergunta = 120 registros) =====
INSERT INTO ALTERNATIVA (DESCRICAO, IS_CORRETA, FK_ID_PERGUNTA) VALUES
('Mede intensidade de luz',1,1),
('Mede temperatura ambiente',0,1),
('Mede umidade do ar',0,1),
('Mede distância por ultrassom',0,1),
('Mede a pressão atmosférica',0,2),
('Detecta movimento',1,2),
('Controla a velocidade de um motor',0,2),
('Mede o nível de um líquido',0,2),
('Um tipo de microcontrolador',0,3),
('Uma linguagem de programação',0,3),
('Internet das Coisas',1,3),
('Um protocolo de criptografia',0,3),
('Detecta luz infravermelha refletida',0,4),
('Mede a resistência elétrica do ar',0,4),
('Capta ondas de radiofrequência',0,4),
('Mede distância por eco',1,4),
('Pinos digitais programáveis',1,5),
('Um protocolo de comunicação serial',0,5),
('Um tipo de memória flash',0,5),
('Uma unidade de medida de tensão',0,5),
('Mede a temperatura interna do motor',0,6),
('Detecta gases inflamáveis',1,6),
('Identifica cores de objetos',0,6),
('Detecta vibrações mecânicas',0,6),
('Um protocolo de rede sem fio',0,7),
('Um tipo de memória RAM',0,7),
('Modulação por largura de pulso',1,7),
('Um sensor de pressão atmosférica',0,7),
('Um sensor analógico de temperatura',0,8),
('Um display de cristal líquido',0,8),
('Um módulo de armazenamento externo',0,8),
('Microcontrolador com Wi-Fi',1,8),
('Protocolo leve de mensagens',1,9),
('Um algoritmo de criptografia AES',0,9),
('Um padrão de comunicação serial RS-232',0,9),
('Um formato de arquivo de imagem',0,9),
('Ambos são sensores de temperatura',0,10),
('Microcontrolador vs microcomputador',1,10),
('Ambos são protocolos de rede',0,10),
('Ambos são linguagens de programação',0,10),
('Sensor de presença infravermelho',0,11),
('Sensor de luminosidade',0,11),
('Sensor de temperatura e umidade',1,11),
('Sensor de corrente elétrica',0,11),
('Mede a intensidade luminosa',0,12),
('Mede o nível de som',0,12),
('Mede a velocidade do vento',0,12),
('Mede calor/temperatura ambiente',1,12),
('Dispositivo que executa ações físicas',1,13),
('Dispositivo que armazena dados',0,13),
('Dispositivo que mede grandezas físicas',0,13),
('Dispositivo que gera energia solar',0,13),
('Sensor de temperatura de alta precisão',0,14),
('Chave elétrica controlada automaticamente',1,14),
('Conversor de sinal analógico em digital',0,14),
('Display para exibir mensagens',0,14),
('Diferença de potencial entre dois pontos',0,15),
('Resistência oferecida por um material',0,15),
('Fluxo de elétrons em um circuito',1,15),
('Capacidade de armazenamento de energia',0,15),
('Um sensor de temperatura digital',0,16),
('Um tipo de bateria recarregável',0,16),
('Um cabo de comunicação serial',0,16),
('Circuito programável para controle',1,16),
('Chip com múltiplos componentes eletrônicos',1,17),
('Um tipo de resistor variável',0,17),
('Um sensor de umidade do solo',0,17),
('Um protocolo de comunicação sem fio',0,17),
('Mede a velocidade do ar',0,18),
('Detecta presença de gases no ambiente',1,18),
('Identifica a cor dos objetos próximos',0,18),
('Controla a abertura de válvulas mecânicas',0,18),
('Transmissão de dados simultânea em vários fios',0,19),
('Armazenamento de dados em memória não volátil',0,19),
('Comunicação entre dispositivos por dados sequenciais',1,19),
('Conversão de sinal analógico em digital',0,19),
('Um sensor de pressão atmosférica',0,20),
('Um tipo de motor de passo',0,20),
('Uma unidade de medida de corrente',0,20),
('Protocolo de comunicação com dois fios',1,20),
('Protocolo de comunicação síncrona rápida',1,21),
('Um sensor infravermelho de presença',0,21),
('Uma fonte de alimentação reguladora',0,21),
('Um algoritmo de compressão de dados',0,21),
('Fluxo de corrente em um condutor',0,22),
('Diferença de potencial elétrico',1,22),
('Capacidade de um componente dissipar calor',0,22),
('Quantidade de energia armazenada em uma bateria',0,22),
('Converte sinal digital em analógico',0,23),
('Amplifica sinais de baixa potência',0,23),
('Converte sinal analógico em digital',1,23),
('Filtra ruídos em sinais de áudio',0,23),
('Converte sinal analógico em digital',0,24),
('Armazena dados em memória externa',0,24),
('Gera sinais de clock para o processador',0,24),
('Converte sinal digital em analógico',1,24),
('Mecanismo que interrompe o fluxo normal para executar tarefa',1,25),
('Falha permanente no funcionamento do chip',0,25),
('Redução proposital da frequência do processador',0,25),
('Bloqueio de comunicação entre periféricos',0,25),
('Aumento da sensibilidade do botão',0,26),
('Técnica para evitar ruído em botões',1,26),
('Conversão do sinal do botão em PWM',0,26),
('Proteção contra sobretensão no botão',0,26),
('Consumo máximo durante a inicialização',0,27),
('Energia gerada por um sensor em repouso',0,27),
('Consumo mínimo de energia em repouso',1,27),
('Capacidade total da bateria do dispositivo',0,27),
('Temporizador usado para gerar PWM',0,28),
('Sensor que monitora a temperatura do chip',0,28),
('Contador de pulsos de um encoder',0,28),
('Timer que reinicia o sistema em falhas',1,28),
('Aumento da frequência de operação do hardware',1,29),
('Redução do consumo de energia do processador',0,29),
('Aumento da capacidade de memória RAM',0,29),
('Sincronização entre múltiplos sensores',0,29),
('Um componente físico de armazenamento',0,30),
('Software embarcado em hardware',1,30),
('Um protocolo de comunicação wireless',0,30),
('Um tipo de sensor analógico',0,30);

-- ===== USUARIOS  =====
INSERT INTO USUARIO (CPF, NOME, EMAIL, SENHA, CARTAO, DATA_NASCIMENTO, TELEFONE, FOTO) VALUES
('446.213.508-38','Matheus Neri','matheus@gmail.com','$2a$12$RKMfDeHF4W7ngIWuDBm9buhlwMTYikgwTVkyskwDtOQxrVc5MfIni','FC93DB06','2008-08-02','(19) 99315-1477','assets/images/Grupo/matheus.png'),
('531.427.148-63','Emily Maiara','emily@gmail.com','$2a$12$RKMfDeHF4W7ngIWuDBm9buhlwMTYikgwTVkyskwDtOQxrVc5MfIni','56171506','2008-08-07','(19) 99508-3585','assets/images/Grupo/emily.png'),
('459.829.728-00','Fernanda Amaral','fernanda@gmail.com','$2a$12$RKMfDeHF4W7ngIWuDBm9buhlwMTYikgwTVkyskwDtOQxrVc5MfIni','0000000000000002','2008-06-22','(19) 99890-8934','assets/images/Grupo/fernanda.png'),
('010.121.232-33','Sophia Perom','sophia@gmail.com','$2a$12$RKMfDeHF4W7ngIWuDBm9buhlwMTYikgwTVkyskwDtOQxrVc5MfIni','0000000000000003','2008-08-03','(19) 99372-9443','assets/images/Grupo/sophia.png'),
('101.202.303-44','Guilherme Staconi','guilherme@gmail.com','$2a$12$RKMfDeHF4W7ngIWuDBm9buhlwMTYikgwTVkyskwDtOQxrVc5MfIni','0000000000000004','2008-08-04','(19) 98993-0927','assets/images/Grupo/guilherme.png'),
('123.456.789-00','Isabela Tessarin','isabela@gmail.com','$2a$12$RKMfDeHF4W7ngIWuDBm9buhlwMTYikgwTVkyskwDtOQxrVc5MfIni','0000000000000005','2008-08-05','(19) 98953-5385','assets/images/Grupo/isabela.png'),
('123.456.789-01','Lorrana Generoso','lorrana@gmail.com','$2a$12$RKMfDeHF4W7ngIWuDBm9buhlwMTYikgwTVkyskwDtOQxrVc5MfIni','0000000000000006','2008-08-06','(19) 99740-8006','assets/images/Grupo/lorrana.png'),

('123.456.789-07','Eduardo Santos Barros','user7@email.com','$2b$10$WYO0ELrEVqMoq1wbi7XfGO7mUiUHoQGt0uBltQwQ2.55Jx7syCKYq','0000000000000007','2001-12-22','(11) 98765-1237',''),
('123.456.789-08','Bianca Oliveira Reis','user8@email.com','$2b$10$UafH2zKBfkpXxFZw8eafm.PlPJT1Jrtupb2q/K2KauimsfvkaCnsK','0000000000000008','1994-05-05','(11) 98765-1238',''),
('123.456.789-09','Rafael Augusto Dias','user9@email.com','$2b$10$.VJWTAWV65FrRDVAqdJ5TuNx24Pgb6FjIorSGUGjl8Suhzwhv9gSK','0000000000000009','1998-08-18','(11) 98765-1239',''),
('123.456.789-10','Letícia Fernandes Cruz','user10@email.com','$2b$10$sRmndqtp5jYSbMOnSjx7TusFOmVuq.ypqE5o5BBWFrb6bQ7Jzdb8y','0000000000000010','2002-02-27','(11) 98765-1240',''),
('123.456.789-11','Vinícius Almeida Teixeira','user11@email.com','$2b$10$VX7d6hyIQAfHBOE3IUHLP.faE0oUPgKq/xRaHmZgsviQy.8pdnqmq','0000000000000011','1990-03-12','(11) 99876-5432',''),
('987.654.321-00','Camila Rodrigues Nunes','user12@email.com','$2b$10$hSSTLdOsD/dZTRHwAE7TXOu9BuZ9ZmkTme7z8dC/go5zLt01FaypC','0000000000000012','1990-03-12','(12) 98765-4321',''),
('111.222.333-44','Felipe Martins Araújo','user13@email.com','$2b$10$JL/yxtoEI43ti.NMGQftxetayna3N5Zcc5a4DuMtJSLNhdTgYP4pK','0000000000000013','1992-11-08','(13) 97654-3210',''),
('222.333.444-55','Daniela Carvalho Pinto','user14@email.com','$2b$10$kFrx2Yi.5ty62eQZCpWfPuUvpmsHTLpbvBCzA71QY4mOwBUnqFJ7.','0000000000000014','1978-01-30','(14) 96543-2109',''),
('333.444.555-66','Matheus Cardoso Farias','user15@email.com','$2b$10$qwzG/yUm8/LfAvn6sPkaYe4wvfPqZjhCYPqr.2A8.u96IdrYKs6EW','0000000000000015','2000-09-14','(15) 95432-1098',''),
('444.555.666-77','Larissa Gomes Moura','user16@email.com','$2b$10$wF4sinWaoQ9vAx9nizvdbevWTbbL4NHRUmWS/U94LILlBjlkWy8/W','0000000000000016','1995-05-22','(16) 94321-0987',''),
('555.666.777-88','Bruno Cesar Ribeiro','user17@email.com','$2b$10$7/sMzgyVc8SVIx0CD5zkmez9TbnShJ8Negghpj07PABaCVEMoRBGq','0000000000000017','1988-12-03','(17) 93210-9876',''),
('666.777.888-99','Tatiane Andrade Lopes','user18@email.com','$2b$10$/85nQQKbQPewcTZkyt0v3u4savVIF8XdH1bXCAqa4mi9RbWt.jvY6','0000000000000018','1993-06-17','(18) 92109-8765',''),
('777.888.999-00','Diego Souza Tavares','user19@email.com','$2b$10$ICQm1hjTEVJSzRYc8GfiMexDeS2yLHRcsaFUX4CSt7bfsBefuSDeO','0000000000000019','1980-04-09','(19) 91098-7654',''),
('888.999.000-11','Aline Pereira Cavalcante','user20@email.com','$2b$10$mDaZ6dokJfk7o3Z.q2Nhu.hwpFtuq2Zwbifgf/mZ/6KWgVBHqDfP2','0000000000000020','2002-08-28','(21) 99911-2233',''),
('101.202.303-54','Renato Marques Duarte','user21@email.com','$2b$10$7WSLuGhPTy7KpdQufQ/xce3WC.PXTGD2Gx0kV9o9Y6kCZirxeoLR.','0000000000000021','1998-02-11','(22) 98822-3344',''),
('202.303.404-55','Fernanda Lima Batista','user22@email.com','$2b$10$F6xdvZoyuU/d7XaJkp5G0uZugYYdTQdPEK0Iw9hutgvfUqfbT0X8a','0000000000000022','1983-10-05','(24) 97733-4455',''),
('303.404.505-66','Lucas Gabriel Freitas','user23@email.com','$2b$10$VUcOizaaPAwEblC8.HRBvOpETOGSjz79gTaMDDQjU7scrK1wRrS4.','0000000000000023','1991-07-19','(37) 98921-2233',''),
('404.505.606-77','Patrícia Nascimento Moreira','user24@email.com','$2b$10$cDzTVW98pgqVWyYqf1S9YuZOMRreK6NKgktKWXLxpO6IMWCva.aze','0000000000000024','1975-03-23','(35) 90010-1122',''),
('505.606.707-88','Igor Castro Cunha','user25@email.com','$2b$10$mS5qSK13WnRyaD6WtWpPSeBE5fPZBlwxHjt/aq.vQsmBGOpkq6wFC','0000000000000025','2001-11-07','(33) 92288-9900',''),
('606.707.808-99','Vanessa Ramos Correia','user26@email.com','$2b$10$52u9EaYLX.l2HEhds8Gd9OfvAHzXMCh8pkXFde31KxTKXCnsv4WVy','0000000000000026','1987-01-16','(31) 94466-7788',''),
('707.808.909-00','Henrique Vieira Macedo','user27@email.com','$2b$10$/KOPM.WeEz1Ucy9QTDUSWeKUp4yo4BpDiXc02pff4hmjKbaTbiziy','0000000000000027','1996-09-29','(28) 95555-6677',''),
('808.909.010-11','Juliana Cunha Pacheco','user28@email.com','$2b$10$IXBZxF.zuJMsnI7QyPA7sOZOsmk024GfIdm4wG7Sq8N6UuhbSrfOe','0000000000000028','1982-06-04','(27) 96644-5566',''),
('909.010.121-22','Marcelo Borges Monteiro','user29@email.com','$2b$10$VkvrCIDArk62ZRSR.w.QGu6BfXaXeFlUwS9SeELvnRvRXyEqUyeHS','0000000000000029','1999-12-21','(34) 91199-0011',''),
('010.121.232-34','Sabrina Nogueira Campos','user30@email.com','$2b$10$7Zar8Ht6MFeXZauDAxFIjO59IveLjoXihDNVTpIcy2/HgXJl5hgwm','0000000000000030','1994-08-10','(32) 93377-8899','');

-- ===== RESPONDE  =====
INSERT INTO RESPONDE (FK_CPF_USUARIO, FK_ID_ALTERNATIVA) VALUES
('446.213.508-38',1),
('531.427.148-63',6),
('459.829.728-00',11),
('010.121.232-33',16),
('101.202.303-44',17),
('123.456.789-00',22),
('123.456.789-07',27),
('123.456.789-08',32),
('123.456.789-09',33),
('123.456.789-10',38),
('123.456.789-00',43),
('987.654.321-00',48),
('111.222.333-44',49),
('222.333.444-55',54),
('333.444.555-66',59),
('444.555.666-77',64),
('555.666.777-88',65),
('666.777.888-99',70),
('777.888.999-00',75),
('888.999.000-11',80),
('101.202.303-44',81),
('202.303.404-55',86),
('303.404.505-66',91),
('404.505.606-77',96),
('505.606.707-88',97),
('606.707.808-99',102),
('707.808.909-00',107),
('808.909.010-11',112),
('909.010.121-22',113),
('010.121.232-33',118);

-- http://(ip)/VISIO_Codeigniter/public/index.php/api/sensores
-- "ID_SENSOR": "1",
-- "NOME": "Módulo de Sinalização Sonora",
-- "FOTO": "assets/images/Sensores/Módulo_de_Sinalização_Sonora.png",
-- "DESCRICAO": "Módulo utilizado para emitir sinais sonoros, alarmes e avisos em projetos eletrônicos.",
-- "CIRCUITO": "Arduino + Módulo de Sinalização Sonora"

-- http://(ip)/VISIO_Codeigniter/public/index.php/api/usuarios
-- "CPF": "010.121.232-33",
-- "NOME": "Sabrina Nogueira Campos",
-- "EMAIL": "user30@email.com",
-- "CARTAO": "6402 8731 5296 1847",
-- "DATA_NASCIMENTO": "1994-08-10",
-- "TELEFONE": "(32) 93377-8899",
-- "FOTO": ""

-- http://(ip)/VISIO_Codeigniter/public/index.php/api/quiz/perguntas
-- "ID_PERGUNTA": "1",
-- "DESCRICAO": "O que mede um sensor LDR?",
-- "NIVEL_DIFICULDADE": "Fácil",
-- "alternativas": [
--     {
--         "ID_ALTERNATIVA": 1,
--         "DESCRICAO": "Mede intensidade de luz",
--         "IS_CORRETA": 1
--     },
--     {
--         "ID_ALTERNATIVA": 2,
--         "DESCRICAO": "Mede temperatura ambiente",
--         "IS_CORRETA": 0
--     },
--     {
--         "ID_ALTERNATIVA": 3,
--         "DESCRICAO": "Mede umidade do ar",
--         "IS_CORRETA": 0
--     },
--     {
--         "ID_ALTERNATIVA": 4,
--         "DESCRICAO": "Mede distância por ultrassom",
--         "IS_CORRETA": 0
--     }
-- ]

-- Comando para rodar o Flutter:
-- flutter run -d web-server --web-hostname (ip) --web-port 5000

-- Acesso pelo navegador:
-- https://(ip):5000