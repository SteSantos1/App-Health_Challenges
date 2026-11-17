create database healthChallenges;
use healthchallenges;

create table usuario(
id_usuario int primary key AUTO_INCREMENT,
nome varchar(100) not null,
email varchar(100) unique not null,
senha varchar (255) not null, -- por conta da criptografia
data_nascimento date not null,
altura decimal(3,2),
peso decimal(5,2),
data_criacao datetime not null DEFAULT CURRENT_TIMESTAMP
);

create table desafio(
id_desafio int primary key AUTO_INCREMENT,
titulo varchar(100) not null,
descricao text,
categoria varchar(10) not null,
data_inicio date not null,
data_fim date not null,
tipo_meta varchar (15) not null,
valor_meta int not null, check (valor_meta > 0)
);

create table grupo(
id_grupo int primary key AUTO_INCREMENT,
nome_grupo varchar(100) not null,
descricao text,
id_criador int not null,
foreign key (id_criador) references usuario(id_usuario)
);

create table atividade(
id_atividade int primary key  AUTO_INCREMENT,
tipo_atividade varchar(20) not null,
valor_atingido int not null,
unidade varchar(10) not null,
data_hora datetime not null,
id_usuario int not null,
foreign key (id_usuario) references usuario(id_usuario)
);

create table participacao(
id_participacao int primary key AUTO_INCREMENT,
progresso_atual int not null,
status varchar(10) not null,
id_usuario int not null,
id_grupo int null,
id_desafio int not null,
foreign key(id_usuario) REFERENCES usuario(id_usuario),
foreign key (id_grupo) REFERENCES grupo(id_grupo),
foreign key (id_desafio) REFERENCES desafio(id_desafio)
);

create table membro_usuario(
id_usuario int not null,
id_grupo int not null,
papel varchar(13) not null,
data_entrada date not null,
foreign key(id_usuario) references usuario(id_usuario),
foreign key(id_grupo) references grupo(id_grupo),
primary key (id_usuario, id_grupo)
);

create table conquista(
id_conquista int primary key AUTO_INCREMENT,
nome varchar(100) not null,
descricao text,
icone_url varchar(255),
criterio_desbloqueio text not null
);

create table usuario_conquista(
id_usuario int not null ,
id_conquista int not null,
data_obtencao date not null,
foreign key(id_usuario) references usuario(id_usuario),
foreign key(id_conquista) references conquista(id_conquista),
primary key (id_usuario, id_conquista)
);

create table amizade(
id_usuario_remetente int not null,
id_usuario_destinatario int not null,
status varchar(9) not null,
data_conexao datetime not null,
foreign key (id_usuario_remetente) references usuario(id_usuario),
foreign key (id_usuario_destinatario) references usuario(id_usuario),
primary key(id_usuario_remetente, id_usuario_destinatario)
);

create table notificacao(
id_notificacao int primary key AUTO_INCREMENT,
titulo varchar(100) not null,
mensagem text not null,
tipo varchar (7) not null,
lida boolean not null,
data_hora datetime not null,
id_usuario int not null,
foreign key (id_usuario) references usuario(id_usuario)
);

create table historico_corporal(
id_historico int primary key AUTO_INCREMENT,
altura decimal(3,2) not null,
peso decimal(5,2) not null,
imc decimal(4,2) not null,
data_registro date not null,
id_usuario int not null,
foreign key (id_usuario) references usuario(id_usuario)
);

create table ranking_temporada(
id_ranking int primary key AUTO_INCREMENT,
mes_ano varchar(7) not null,
pontuacao_periodo int not null,	check (pontuacao_periodo >= 0),
id_usuario int not null,
foreign key (id_usuario) references usuario(id_usuario)
);

create table categoria_saude(
id_categoria int primary key AUTO_INCREMENT,
nome_categoria varchar(100) not null,
cor_hexadecimal varchar(7) not null
);

create table acao_base(
id_acao int primary key AUTO_INCREMENT,
nome_acao varchar(100) not null,
nivel_dificuldade decimal(2,1) not null,
unidade_medida varchar(10) not null,
fator_pontos int not null,
id_categoria int not null,
foreign key (id_categoria) references categoria_saude(id_categoria)
);

create table desafio_diario(
id_desafio_diario int primary key AUTO_INCREMENT,
data_sorteio date not null,
quantidade_exigida int not null,
pontos_recompensa int not null,
id_acao int not null,
foreign key (id_acao) references acao_base(id_acao)
);

create table registro_conclusao(
id_registro int primary key AUTO_INCREMENT,
quantidade_feita int not null, check (quantidade_feita >= 0),
data_hora datetime not null,
status_conclusao boolean not null,
id_usuario int not null,
id_desafio_diario int not null,
foreign key (id_usuario) references usuario(id_usuario),
foreign key (id_desafio_diario) references desafio_diario(id_desafio_diario)
);

create table ranking_diario(
id_ranking_dia int primary key AUTO_INCREMENT,
data_ranking date not null,
pontos_do_dia int not null,
id_usuario int not null,
foreign key (id_usuario) references usuario(id_usuario)
);