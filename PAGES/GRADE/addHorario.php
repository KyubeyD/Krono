<?php 
    require_once __DIR__ . '/../../ASSETS/conexao.php';

    if (isset($_POST['id_escola'])) {
        $id_escola = $_POST['id_escola'];

        if (isset($_POST['carga_diaria'])) {
            $inicio_semana = $_POST['inicio_semana'];
            $final_semana = $_POST['final_semana'];
            $numero_semana = array(1,2,3,4,5,6,7);
            $horas_diarias = $_POST['carga_diaria'];
            $horario_inicial = $_POST['horario_inicial'];
            $divisao_tempos = $_POST['divisao'];
            $minutos_intervalo = $_POST['minutos_intervalo'];
            $tempo_intervalo = $_POST['tempo_intervalo'];

            $dias = array();
            for ($i = $inicio_semana; $i <= $final_semana; $i++) {
                $dias[] = $i;
            }

            $carga_diaria = $horas_diarias * 60;

            $tempos = array();
            for ($i = 1; $carga_diaria > 0; $i++) {
                $tempos[] = $i;
                $carga_diaria -= $divisao_tempos;
            }
            
            foreach ($dias as $dia) {
                $hora_inicial = new DateTime($horario_inicial . ":00");
                $hora_final = clone $hora_inicial;
                $hora_final->modify('+' . $divisao_tempos . 'minutes');
                
                foreach ($tempos as $tempo) {
                    $str_hora_inicio = $hora_inicial->format('H:i:s');
                    $str_hora_final = $hora_final->format('H:i:s');
                    //echo  "<br>" . $str_hora_inicio . " - " . $str_hora_final;
                    
                    $hora_inicial_comparacao = $hora_inicial->format('H:i');
                    // Switch ajustado para comparar corretamente os períodos
                    switch (true) {
                        case ($hora_inicial_comparacao >= '07:00' && $hora_inicial_comparacao < '12:00'):
                            $periodo = 'M'; // Manhã
                            break;
                        case ($hora_inicial_comparacao >= '12:00' && $hora_inicial_comparacao < '18:00'):
                            $periodo = 'T'; // Tarde
                            break;
                        case ($hora_inicial_comparacao >= '18:00'):
                            $periodo = 'N'; // Noite
                            break;
                        default:
                            $periodo = null; // Deixe como null para evitar inserir um valor errado
                            break;
                    }

                    $sql = "INSERT INTO grade_horaria_escolar (turno, tempo, duracao, horario_inicio, horario_final, dia_semana, dt_cadastro, id_escola) 
                    VALUES (:turno, :tempo, :duracao, :horario_inicio, :horario_final, :dia_semana, DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), :id_escola)";

                    try {
                        $query = $conexao->prepare($sql);
                        $query->bindValue(':turno', $periodo, PDO::PARAM_STR); // Turno é um CHAR(1)
                        $query->bindValue(':tempo', $tempo, PDO::PARAM_INT); // Tempo é INT(1), portanto é seguro usar PDO::PARAM_INT
                        $query->bindValue(':duracao', $divisao_tempos, PDO::PARAM_INT); // Tempo é INT(1), portanto é seguro usar PDO::PARAM_INT
                        $query->bindValue(':horario_inicio', $str_hora_inicio, PDO::PARAM_STR); // Horário de início é do tipo TIME
                        $query->bindValue(':horario_final', $str_hora_final, PDO::PARAM_STR); // Horário final é do tipo TIME
                        $query->bindValue(':dia_semana', $dia, PDO::PARAM_INT); // Dia da semana é CHAR(1)
                        $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT); // ID da escola é um INT
                        $query->execute();
                    } catch (PDOException $e) {
                        echo "Erro ao inserir horário: " . $e->getMessage();
                    }

                    foreach ($tempo_intervalo as $index => $tempo_inter) {
                        if ($tempo == $tempo_inter) {
                            // Encontra o minuto correspondente ao tempo de intervalo
                            $minuto = $minutos_intervalo[$index]; 
                            // Adiciona o tempo de intervalo à hora final
                            $hora_inicial->modify('+' . $minuto . ' minutes');
                            $hora_final->modify('+' . $minuto . ' minutes');
                        }
                    }
                    
                    $hora_inicial->modify('+' . $divisao_tempos . ' minutes');
                    $hora_final->modify('+' . $divisao_tempos . ' minutes');

                }
            }


            header('location: ../../index.php?page=meu_horario_escola');
        }
    }

?>