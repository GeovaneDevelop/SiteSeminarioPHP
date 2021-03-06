<?php 

namespace App\Models\inscricao;

class Submicao{

    private $connect;

    public function __construct(){
        $this->connect = new \Config\Connect();
    } 

    public function insertSubmicao($dados){
        
        if($dados['demais_autores']){
            $dados['demais_autores'] = "";
        }

        $sql = "
             INSERT INTO `trabalho` 
            (
                 `id`, `titulo`, `conhecimento`, `primeiro_autor`,
                 `demais_autores`, `orientador`, `coordenador`, `inscricao`
            ) 
            VALUES(
                 '', '$dados[titulo]', '$dados[conhecimento]', '$dados[primeiro_autor]',
                 '$dados[demais_autores]', '$dados[orientador]', 
                 '$dados[coordenador]', '$dados[inscricao]'
            )
        ";
        
        if ($this->connect->getConnection()->query($sql) == true){
            echo "Criado com sucesso";
            return ["status" => 200, "resultado" => "Criado com sucesso"];;
        }else{
            echo "Falha ao criar registro " . mysqli_error($this->connect->getConnection()); 
            $error = $this->connect->getConnection()->error;
            return ["status" => 405, "resultado" => "Falha ao criar registro: $error"];;
        }
    }

    public function insertInscricaoAdmin($dados){
        date_default_timezone_set('America/Sao_Paulo');
        $date = date('d-m-Y');
        if($dados['numero_transacao']==0){
            $dados['numero_transacao'] =  $dados['cpf'];
        }
        $sql = "
            INSERT INTO inscricaoevento 
            (
                nome, email, telefone, cpf, dados_institucionais, instituicao, 
                curso, data_criacao, tipo_inscricao, numero_transacao, status_pagamento, credenciado
            )
            VALUES 
            (
                '$dados[nome]', '$dados[email]', '$dados[telefone]', '$dados[cpf]', 
                '$dados[dados_institucionais]', '$dados[instituicao]', '$dados[curso]',
                '$date','$dados[tipo_inscricao]', '$dados[numero_transacao]', 
                'não', 'Não credenciado'
            )
        ";
        
        if ($this->connect->getConnection()->query($sql) == true){
            echo "Criado com sucesso";
            return ["status" => 200, "resultado" => "Criado com sucesso"];;
        }else{
            echo "Falha ao criar registro " . mysqli_error($this->connect->getConnection()); 
            $error = $this->connect->getConnection()->error;
            return ["status" => 405, "resultado" => "Falha ao criar registro: $error"];;
        }
    }


    public function getTrabalhos(){
        
        $sql = "SELECT * FROM trabalho order by titulo asc
        ";
        $result = $this->connect->getConnection()->query($sql);

        if (!$result){
            return ["status" => 404, "resultado" => "Nada encontrado"];
        }else{
            $i = 0;
            while ($dados = mysqli_fetch_assoc($result)){
                $array[$i] = $dados;
                $i++;
            }
        

            return ["status" => 200, "resultado" => $array];
        }
    }


    public function getSubmicaoVerificada($id){
        
        $sql = "select * from trabalho where inscricao = '$id'";
        $result = $this->connect->getConnection()->query($sql); 
        $resultado = mysqli_fetch_assoc($result);

        if($resultado){
            return ["status" => 200, "resultado" => $resultado];
        }else{
            return ["status" => 404, "resultado" => "Nada encontrado"];
        }
    }


    public function updateInscricao($dados){
        date_default_timezone_set('America/Sao_Paulo');
        $date = date('d-m-Y');
        if($dados['numero_transacao'] == ""){
            $dados['numero_transacao'] = $dados['cpf'];
        }
        $sql = "
        UPDATE `inscricaoevento` SET `nome` = '$dados[nome]', `email` = '$dados[email]', 
        `telefone` = '$dados[telefone]', `cpf` = '$dados[cpf]',
        `dados_institucionais` = '$dados[dados_institucionais]', 
        `instituicao` = '$dados[instituicao]', `curso` = '$dados[curso]',
        `tipo_inscricao` = '$dados[tipo_inscricao]', `numero_transacao` = '$dados[numero_transacao]',
        `status_pagamento` = '$dados[status_pagamento]', `data_criacao` = '$dados[data_criacao]',
        `data_modificacao` = '$date', `credenciado` = '$dados[credenciado]'
        WHERE `inscricaoevento`.`id` = '$dados[id]'; 
        ";
      
        if($this->connect->getConnection()->query($sql)==true){
            echo "Atualizado com sucesso" . $this->connect->getConnection()->error;
            return ["status" => 200, "resultado" => "Atualizado com sucesso"];
        }else{
            
            $error = $this->connect->getConnection()->error;
            echo "Falha ao criar registro" . $error;
            return ["status" => 405, "resultado" => "Falha ao atualizar registro:  $error "];
        }        
    }

    function deletaTrabalho($id){
        $sql = "
            DELETE FROM trabalho
            WHERE id='$id'
        ";
        if ($this->connect->getConnection()->query($sql) == true){
            echo "Resgistro apado";
            return ["status" => 200, "resultado" => "Apagado com sucesso"];
        }else{
            echo "Erro ao apagar registro";
            $error = $this->connect->getConnection()->error;
            return ["status" => 405, "resultado" => "Resgistro não apagado: $error"];
        }
    }

}