UPDATE users
SET 
    -- coloque o seu email (exemplo: joaozinho+[user_id]@empreendemia.com.br)
	login = concat('testes+',id,'@empreendemia.com.br'),
	email = concat('testes+',id,'@empreendemia.com.br'),
    -- senha testando
	password = '7f3f79aa445a213da9a902fb75501552e0bf5976'
