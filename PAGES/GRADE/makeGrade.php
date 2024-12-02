    <link rel="stylesheet" href="CSS/dashboard.css">
    <link rel="stylesheet" href="CSS/grade.css">
    <link rel="stylesheet" href="CSS/modal.css">
    <link rel="stylesheet" href="CSS/makeGrade.css">
    <link rel="stylesheet" href="CSS/topbar.css">
    <div class="container-grade">

        <?php 
            session_start();
            $id_turma = $_GET['id_turma'];
            $sql = "SELECT * FROM turma WHERE id_turma = :id_turma";
            $query = $conexao->prepare($sql);
            $query->bindValue(':id_turma', $id_turma, PDO::PARAM_INT);
            $query->execute();
            $turma = $query->fetch(PDO::FETCH_ASSOC);
            $id_escola = $turma['id_escola'];

            $sql = "SELECT id_professor FROM vinculo_prof_esc WHERE id_escola = :id_escola";
            $query = $conexao->prepare($sql);
            $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
            $query->execute();
            $id_professores = $query->fetchAll(PDO::FETCH_ASSOC);
        ?>

            <div class="head-crud">
                <h2 class="title"><a href="?page=grades_horarias"><i class='bx bx-left-arrow-alt'></i></a>Grade da <?php echo $turma['nome_turma']; ?></h2>
            </div>    
            <?php
                // Verificar se há alguma grade inserida
                $sql = "SELECT MAX(dt_cadastro) AS dt_atual, COUNT(*) AS total FROM grade_horaria_escolar WHERE id_escola = :id_escola";
                $verificar_grade = $conexao->prepare($sql);
                $verificar_grade->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
                $verificar_grade->execute();
                $resultado_grade = $verificar_grade->fetch(PDO::FETCH_ASSOC);
                $dt_atual = $resultado_grade['dt_atual'];

                $sql = "SELECT * FROM disciplina WHERE id_escola = :id_escola";
                $query = $conexao->prepare($sql);
                $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
                $query->execute();
                $disciplinas = $query->fetchAll(PDO::FETCH_ASSOC);
        
                if ($resultado_grade['total'] > 0):
            ?>
            <div class="content-main">
            
            <div class="content-cargah">
                <div class="head-content-cargah">
                    <h1>Disciplinas</h1>
                </div>
                <div class="body-content-cargah">
                    <table id="tabelaDiscipllinas">
                        <tr>
                            <td>Sigla</td>
                            <td>Disciplina</td>
                            <td>C.H.</td>
                            <td>%</td>
                        </tr>
                        <?php 
                            foreach ($disciplinas as $disciplina):
                        ?>
                            <tr class="disciplinas" data-disciplina="<?php echo $disciplina['id_disciplina']; ?>" data-ch="<?php echo $disciplina['carga_horaria']; ?>">
                                <td><?php echo $disciplina['sigla_disciplina']; ?></td>
                                <td><?php echo $disciplina['nome_disciplina']; ?></td>
                                <td><?php echo $disciplina['carga_horaria'] . "h"; ?></td>
                                <td>100%</td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
            
            <div id="modalCadastro" class="modal" style="display:none;">
                <div class="modal-content">
                    <span class="close" onclick="document.getElementById('modalCadastro').style.display='none'">&times;</span>
                    <h3 class="modal-title">Selecione a Disciplina e o Professor</h3>
                    <p id="horarioInfoDisplay"></p>
                    <form>
                        <div class="form-modal">
                            <label for="disciplinaSelect" class="modal-label">Disciplina:</label>
                            <select id="disciplinaSelect" name="disciplina" class="modal-form" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($disciplinas as $disciplina): ?>
                                    <option value="<?php echo $disciplina['id_disciplina']; ?>">
                                        <?php echo $disciplina['nome_disciplina']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-modal">
                            <label for="professorSelect" class="modal-label">Professor:</label>
                            <select id="professorSelect" name="professor" class="modal-form" required disabled>
                                <option value="">Selecione uma disciplina primeiro</option>
                            </select>
                        </div>
                        <button type="button" class="btn-modal" id="btnSalvar" data-tempo="" onclick="saveSelection()">Salvar</button>
                    </form>
                </div>
            </div>
        
                <div class="content-grade form make">
                    <form action="PAGES/GRADE/addGradeTurma.php" method="post" class="form-grade">
                        <input type="hidden" name="id_turma" value="<?php echo $id_turma; ?>">
                        <table class="grade">
                            <tr class="linhas">
                                <td class="tempo dia_semana">Horário</td>
                                <?php
                                    $sql = "SELECT MAX(tempo) AS maior_tempo, MIN(dia_semana) AS menor_dia, MAX(dia_semana) AS maior_dia 
                                    FROM grade_horaria_escolar 
                                    WHERE id_escola = :id_escola AND dt_cadastro = :dt_cadastro";
                                    $montar_grade = $conexao->prepare($sql);
                                    $montar_grade->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
                                    $montar_grade->bindValue(':dt_cadastro', $dt_atual, PDO::PARAM_STR);
                                    $montar_grade->execute();
        
                                    $montar_grade_row = $montar_grade->fetch(PDO::FETCH_ASSOC);
                                    $maior_tempo = $montar_grade_row['maior_tempo'];
                                    $menor_dia = $montar_grade_row['menor_dia'];
                                    $maior_dia = $montar_grade_row['maior_dia'];
                                    $dias_semana = array('Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado');
        
                                    for ($i = 0; $i <= $maior_dia; $i++) {
                                        if ($i >= $menor_dia) {
                                            echo "<td class='tempo dia_semana' id='dia_" . $i ."'>" . $dias_semana[$i-1] ."</td>"; 
                                        }
                                    }
                                ?>
                            </tr>
                            <?php for ($tempo = 1; $tempo <= $maior_tempo; $tempo++): ?>
                                <?php 
                                    $sql = "SELECT turno, tempo, duracao, horario_inicio, horario_final, dia_semana 
                                    FROM grade_horaria_escolar 
                                    WHERE id_escola = :id_escola AND tempo = :tempo AND dt_cadastro = :dt_cadastro
                                    ORDER BY horario_inicio, dia_semana ASC";    
                                    $query = $conexao->prepare($sql);
                                    $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
                                    $query->bindValue(':tempo', $tempo, PDO::PARAM_INT);
                                    $query->bindValue(':dt_cadastro', $dt_atual, PDO::PARAM_STR);
                                    $query->execute();   
                                    
                                    $sql = "SELECT MIN(horario_inicio) AS horario_inicio, MIN(horario_final) AS horario_final 
                                    FROM grade_horaria_escolar 
                                    WHERE id_escola = :id_escola AND tempo = :tempo AND dt_cadastro = :dt_cadastro";
                                    $horario = $conexao->prepare($sql);
                                    $horario->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
                                    $horario->bindValue(':tempo', $tempo, PDO::PARAM_INT);
                                    $horario->bindValue(':dt_cadastro', $dt_atual, PDO::PARAM_STR);
                                    $horario->execute();
                                    $horario_row = $horario->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <tr class="linhas">
                                    <td class='tempo' id='<?php echo $horario_row['horario_inicio'] . "_" . $horario_row['horario_final'];?>'><?php echo $horario_row['horario_inicio'] . " - " . $horario_row['horario_final']; ?></td>
                                    <?php 
                                        while ($dados = $query->fetch(PDO::FETCH_ASSOC)):
                                    ?>
                                    <td class='tempo input-tempo' data-minutos="<?php echo $dados['duracao'] ?>" id='tempo_<?php echo $tempo ."_dia_" . $dados['dia_semana'] . "_turno_" . $dados['turno']; ?>'>
                                        <div class="btn-make-modal" ><i class='bx bx-plus'></i></div>
                                    </td>
                                    <?php endwhile; ?>
                                </tr>
                            <?php endfor; ?>
                        </table>
                        <input type="submit" value="Enviar" class="btn-make">
                    </form>
                </div>
        
                <div class="content-cargah">
                    <div class="head-content-cargah">
                        <h1>Professores</h1>
                    </div>
                    <div class="body-content-cargah">

                        <table id="tabelaProfessores" class="tabelaDiscProf" style="display:table;">
                            <tr>
                                <td>Nome</td>
                                <td>C.H.</td>
                                <td>%</td>
                                <td>Disp.</td>
                            </tr>
                            <?php 
                                foreach ($id_professores as $id_professor):
                            ?>
                                <?php
                                    $sql = "SELECT 
                                        p.nome_professor, 
                                        cse.horas_semanais
                                    FROM 
                                        professor p
                                    JOIN 
                                        vinculo_prof_carga vpc ON p.id_professor = vpc.id_professor
                                    JOIN 
                                        carga_horaria_esc cse ON vpc.id_carga_horaria_esc = cse.id_carga_horaria_esc
                                    WHERE 
                                        p.id_professor = :id_professor";
                                    $query = $conexao->prepare($sql);
                                    $query->bindValue(':id_professor', $id_professor['id_professor'], PDO::PARAM_INT);
                                    $query->execute();
                                    $professor = $query->fetch(PDO::FETCH_ASSOC);
                                    $nomeCompleto = explode(" ", $professor['nome_professor']);
                                    if (count($nomeCompleto) > 1) {
                                        $nome = $nomeCompleto[0] . " " . $nomeCompleto[1];
                                    } else {
                                        $nome = $professor['nome_professor'];
                                    }
                                ?>
                            <tr class="professores" data-professor="<?php echo $id_professor['id_professor']; ?>" data-hs="<?php echo $professor['horas_semanais']; ?>">
                                <td><?php echo $nome; ?></td>
                                <td><?php echo $professor['horas_semanais'] . "h"; ?></td>
                                <td>100%</td>
                                <td><span class="viewDispBtn" data-id-professor="<?php echo $id_professor['id_professor']; ?>">Ver</span></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
                <?php else: ?>
                    <p class="aviso">Você ainda não criou sua grade.</p>
                <?php endif; ?>
            </div>
    </div>
    <script>
        const idEscola = <?php echo $id_escola; ?>;
    </script>
    <script src="JS/makeGrade.js"></script>