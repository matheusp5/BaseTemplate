CREATE TABLE `ticket`(
    `SecretId` VARCHAR(255) NOT NULL, -- Usado para buscar dados do usuario como: getAllBySecretId e aplicar punições (ban, unban)
    `PublicId` VARCHAR(255) NOT NULL, -- Usado para funcoes ou pedidos externos como: pedidos no MercadoPago/Pagseguro etc
    `Email` VARCHAR(255) NOT NULL, -- Email 
    `Password` VARCHAR(255) NOT NULL, -- Senha
    `Name` VARCHAR(255) NOT NULL, -- Nome
    `Username` VARCHAR(255) NOT NULL, -- Nome de Usuário (Nome de exibição)
    `Banned` BOOLEAN NOT NULL, -- Banido (true ou false)
    `RegisterDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP -- Data e hora de quando foi registrado
) ENGINE = MyISAM;