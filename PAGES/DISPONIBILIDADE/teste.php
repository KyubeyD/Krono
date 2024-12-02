<link rel="stylesheet" href="CSS/dashboard.css">
<link rel="stylesheet" href="CSS/sidebar.css">
<link rel="stylesheet" href="CSS/topbar.css">
<link rel="stylesheet" href="CSS/content.css">
<link rel="stylesheet" href="CSS/grade.css">
<link rel="stylesheet" href="CSS/disp.css">
<?php 
    require_once 'PAGES/COMPONENTS/sidebar.php';
?>
<div class="content">
    <?php 
        require_once 'PAGES/COMPONENTS/topbar.php';
        $id_escola = $_GET['id_escola'];
    ?>

    <div class="container">
        <h1 class="title-page">
            <a href="?page=dashboard">Dashboard</a> > 
            <a href="?page=disponibilidade_professor">Disponibilidade</a> > 
            <a href="?page=orientacoes&id_escola=<?php echo $id_escola; ?>">Orientações</a> > 
            <a href="?page=disponibilidade_professor&id_escola=<?php echo $id_escola; ?>">Minha disponibilidade</a>
        </h1>
        <div class="head-crud">
            <h2>Disponibilidade de horário</h2>
            <div class="carga-horaria">
                <div class="select-emissao">
                    <label for="carga_horaria_semanal">Carga horária semanal: </label>
                    <div class="select">
                        <select name="carga_horaria_semanal" id="carga_horaria_semanal" class="emissao">
                            <option value="">Selecione...</option>
                            <option value="20">20h</option>
                            <option value="40">40h</option>
                            <option value="60">60h</option>
                        </select>
                    </div>
                </div>
                <div class="cont">
                    <p><div class="color" id="PO"></div>Primeira opção: <span class="restante">0</span></p>
                    <p><div class="color" id="CE"></div>C. estudos: <span class="restante">0</span></p>
                    <p><div class="color" id="DH"></div>Demais: <span class="restante">0</span></p>
                </div>
            </div>
        </div>

        <?php
            // Verificar se há alguma grade inserida
            $sql = "SELECT MAX(dt_cadastro) AS dt_atual, COUNT(*) AS total FROM grade_horaria_escolar WHERE id_escola = :id_escola";
            $verificar_grade = $conexao->prepare($sql);
            $verificar_grade->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
            $verificar_grade->execute();
            $resultado_grade = $verificar_grade->fetch(PDO::FETCH_ASSOC);
            $dt_atual = $resultado_grade['dt_atual'];

            $sql = "SELECT id_professor FROM professor WHERE id_usuario = :id_usuario";
            $query = $conexao->prepare($sql);
            $query->bindValue(':id_usuario', $_SESSION['id_usuario'], PDO::PARAM_INT);
            $query->execute();
            $professor = $query->fetch(PDO::FETCH_ASSOC);
            $id_professor = $professor['id_professor'];

            if ($resultado_grade['total'] > 0):
        ?>
        <div class="content-grade form">
            <form action="PAGES/DISPONIBILIDADE/addDispoProf.php" method="post" class="form-grade">
                <input type="hidden" name="id_escola" value="<?php echo $id_escola; ?>">
                <input type="hidden" name="id_professor" value="<?php echo $id_professor; ?>">
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
                            $dias_semana = array('DOM','SEG','TER','QUAR','QUI','SEX','SÁB');

                            for ($i = 0; $i <= $maior_dia; $i++) {
                                if ($i >= $menor_dia) {
                                    echo "<td class='tempo dia_semana' id='dia_" . $i ."'>" . $dias_semana[$i-1] ."</td>"; 
                                }
                            }
                        ?>
                    </tr>
                    <?php for ($tempo = 1; $tempo <= $maior_tempo; $tempo++): ?>
                        <?php 
                            $sql = "SELECT turno, tempo, horario_inicio, horario_final, dia_semana 
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
                            <td class='tempo input-tempo' id='tempo_<?php echo $tempo ."_dia_" . $dados['dia_semana']; ?>'>
                            <div class="select-container">
                                <div class="availability-block" id="btn-pr" data-short-text="P" data-full-text="Primeira Opção">P</div>
                                <input type="checkbox" class="selected-checkbox" hidden>
                                
                                <div class="availability-block" id="btn-ce" data-short-text="C" data-full-text="C. Estudos">C</div>
                                <input type="checkbox" class="selected-checkbox" hidden>
                                
                                <div class="availability-block" id="btn-de" data-short-text="D" data-full-text="Demais">D</div>
                                <input type="checkbox" class="selected-checkbox" hidden>
                            </div>
                            <input type="checkbox" class="selected-checkbox" hidden>
                            </td>
                            <?php endwhile; ?>
                        </tr>
                    <?php endfor; ?>
                </table>
                <input type="submit" value="Enviar" class="btn-grade">
            </form>
        </div>
        <?php else: ?>
            <p class="aviso">Essa escola ainda não disponibilizou a grade.</p>
        <?php endif; ?>
    </div>
</div>
<script src="JS/disp.js"></script>
<script src="JS/tiposdisp.js"></script>