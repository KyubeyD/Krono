<link rel="stylesheet" href="CSS/dashboard.css">
    <link rel="stylesheet" href="CSS/sidebar.css">
    <link rel="stylesheet" href="CSS/topbar.css">
    <link rel="stylesheet" href="CSS/content.css">
    <link rel="stylesheet" href="CSS/modal.css">
    <link rel="stylesheet" href="CSS/grade.css">
    <?php 
        require_once 'PAGES/COMPONENTS/sidebar.php';
    ?>
    <div class="content">
        <?php 
            require_once 'PAGES/COMPONENTS/topbar.php';
            $sql = "SELECT id_escola FROM escola WHERE id_usuario = :id_usuario";
            $queryUsu = $conexao->prepare($sql);
            $queryUsu->bindValue(':id_usuario', $_SESSION['id_usuario'], PDO::PARAM_INT);
            $queryUsu->execute();
            $dadosUsu = $queryUsu->fetch(PDO::FETCH_ASSOC);
            $id_escola = $dadosUsu['id_escola'];

            $id_turma = $_GET['id_turma'];
            $sql = "SELECT * FROM turma WHERE id_turma = :id_turma";
            $query = $conexao->prepare($sql);
            $query->bindValue(':id_turma', $id_turma, PDO::PARAM_INT);
            $query->execute();
            $turma = $query->fetch(PDO::FETCH_ASSOC);
        ?>

        <div class="container">
            <h1 class="title-page"><a href="?page=dashboard">Dashboard</a> > <a href="?page=view_grade&id_turma=<?php echo $turma['id_turma']; ?>"><?php echo $turma['nome_turma']; ?></a></h1>
            <div class="head-crud">
                <h2>Grade da <?php echo $turma['nome_turma']; ?></h2>
            </div>
            <?php

                // Verificar se há alguma grade inserida
                $sql = "SELECT COUNT(*) AS total FROM grade_hora_turma WHERE id_turma = :id_turma";
                $verificar_grade = $conexao->prepare($sql);
                $verificar_grade->bindValue(':id_turma', $turma['id_turma'], PDO::PARAM_INT);
                $verificar_grade->execute();
                $resultado_grade = $verificar_grade->fetch(PDO::FETCH_ASSOC);

                if ($resultado_grade['total'] > 0):
            ?>
        <div class="content-grade">
            <table class="grade">
                    <tr class="linhas">
                        <td class="tempo dia_semana">Horário</td>
                        <?php
                            $sql = "SELECT MAX(tempo) AS maior_tempo, 
                            (SELECT MIN(dia_semana) FROM grade_horaria_escolar WHERE id_escola = :id_escola) AS menor_dia, 
                            (SELECT MAX(dia_semana) FROM grade_horaria_escolar WHERE id_escola = :id_escola) AS maior_dia
                            FROM grade_hora_turma 
                            WHERE id_turma = :id_turma";
                            $montar_grade = $conexao->prepare($sql);
                            $montar_grade->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
                            $montar_grade->bindValue(':id_turma', $turma['id_turma'], PDO::PARAM_INT);
                            $montar_grade->execute();

                            $montar_grade_row = $montar_grade->fetch(PDO::FETCH_ASSOC);
                            $maior_tempo = $montar_grade_row['maior_tempo'];
                            $menor_dia = $montar_grade_row['menor_dia'];
                            $maior_dia = $montar_grade_row['maior_dia'];
                            $dias_semana = array('Domingo','Segunda', 'Terça','Quarta','Quinta','Sexta','Sábado');

                            for ($i = 0; $i <= $maior_dia; $i++) {
                                if ($i >= $menor_dia) {
                                    echo "<td class='tempo dia_semana' id='dia_" . $i ."'>" . $dias_semana[$i-1] ."</td>"; 
                                }
                            }
                        ?>
                    </tr>
                    <?php for ($tempo = 1; $tempo <= $maior_tempo; $tempo++): ?>
                        <?php 
                            if (isset($_GET['data_emissao'])) {
                                $data_emissao = $_GET['data_emissao'];

                                $sql = "SELECT turno, tempo, horario_inicio, horario_final, dia_semana 
                                FROM grade_hora_turma 
                                WHERE id_turma = :id_turma AND tempo = :tempo AND dt_cadastro = :dt_cadastro
                                ORDER BY horario_inicio, dia_semana ASC";
                            } else {
                                /*

                                $sql = "SELECT MAX(dt_cadastro) as mais_recente FROM grade_hora_turma WHERE id_turma = :id_escola";
                                $query = $conexao->prepare($sql);
                                $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
                                $query->execute();
                                $data = $query->fetch(PDO::FETCH_ASSOC);
                                $row_data = $data['mais_recente']; 

                                */

                                $sql = "SELECT turno, tempo, horario_inicio, horario_final, dia_semana 
                                FROM grade_horaria_escolar 
                                WHERE id_escola = :id_escola AND tempo = :tempo
                                ORDER BY horario_inicio, dia_semana ASC";    
                            }
                            $query = $conexao->prepare($sql);
                            $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
                            $query->bindValue(':tempo', $tempo, PDO::PARAM_INT);
                            $query->execute();   
                            
                            $sql = "SELECT MIN(horario_inicio) AS horario_inicio, MIN(horario_final) AS horario_final 
                            FROM grade_horaria_escolar 
                            WHERE id_escola = :id_escola AND tempo = :tempo";
                            $horario = $conexao->prepare($sql);
                            $horario->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
                            $horario->bindValue(':tempo', $tempo, PDO::PARAM_INT);
                            $horario->execute();
                            $horario_row = $horario->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <tr class="linhas">
                            <td class='tempo' id='<?php echo $horario_row['horario_inicio'] . "_" . $horario_row['horario_final'];?>'><?php echo $horario_row['horario_inicio'] . " - " . $horario_row['horario_final']; ?></td>
                            <?php 
                                while ($dados = $query->fetch(PDO::FETCH_ASSOC)):
                                    $sqlTurma = "
                                        SELECT 
                                            ght.id_disciplina, 
                                            ght.id_professor, 
                                            d.nome_disciplina AS nome_disciplina, 
                                            p.nome_professor AS nome_professor
                                        FROM grade_hora_turma ght
                                        INNER JOIN disciplina d ON ght.id_disciplina = d.id_disciplina
                                        INNER JOIN professor p ON ght.id_professor = p.id_professor
                                        WHERE ght.tempo = :tempo 
                                        AND ght.dia_semana = :dia_semana";
                                        try {
                                            $queryTurma = $conexao->prepare($sqlTurma);
                                            $queryTurma->bindValue(':tempo', $tempo, PDO::PARAM_INT);
                                            $queryTurma->bindValue(':dia_semana', $dados['dia_semana'], PDO::PARAM_INT);
                                            $queryTurma->execute();
                                        } catch (PDOException $e) {
                                            echo "Erro ao buscar professores e disciplinas:" . $e->getMessage; 
                                        }
                                        $discProf = $queryTurma->fetch(PDO::FETCH_ASSOC);
                                        if ($discProf):
                                            $nomeProfessor = $discProf['nome_professor'];
                                            $explodeNome = explode(" ", $nomeProfessor);
                                            if (count($explodeNome) > 1) {
                                                $doisNomes = $explodeNome[0] . " " . $explodeNome[1];
                                            } else {
                                                $doisNomes = $nomeProfessor;
                                            }
                                        ?>
                                        <td class='tempo' id='tempo_<?php echo $tempo ."_dia_" . $dados['dia_semana']; ?>'>
                                            <div class="disciplina-grade view"><?php echo $discProf['nome_disciplina']; ?></div>
                                            <div class="professor-grade view"><?php echo $doisNomes; ?></div>
                                        </td> 
                                    <?php else: ?>
                                        <td class='tempo' id='tempo_<?php echo $tempo ."_dia_" . $dados['dia_semana']; ?>'></td> 
                                    <?php endif; ?>    
                            <?php endwhile; ?>
                        </tr>
                    <?php endfor; ?>
            </table>
        </div>
<?php else: ?>
    <p class="aviso">Nenhuma grade foi inserida para a escola.</p>
<?php endif; ?>
        </div>
    </div>
    <script src="JS/modal.js"></script>
    <script src="JS/formgrade.js"></script>
    <script src="JS/redirectdt.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="JS/mask.js"></script>
    