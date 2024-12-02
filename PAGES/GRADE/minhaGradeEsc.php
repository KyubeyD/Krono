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
        ?>

        <div class="container">
            <h1 class="title-page"><a href="?page=dashboard">Dashboard</a> > <a href="?page=meu_horario_escola">Meu horário</a></h1>
            <div class="head-crud">
                <h2>Molde da grade</h2>
                <div class="func-head">
                    <!-- Adicione a funcionalidade aqui -->
                    <div class="select-emissao">
                        <label for="data_emissao">Data de emissão: </label>
                        <div class="select">
                            <select name="emissao" id="emissao" class="emissao" onchange="redirectToPage()">
                            <option value="index.php?page=meu_horario_escola">Selecione uma data</option>
                            <?php
                                $sql = "SELECT DISTINCT dt_cadastro FROM grade_horaria_escolar WHERE id_escola = :id_escola ORDER BY dt_cadastro DESC";
                                $queryDatas = $conexao->prepare($sql);
                                $queryDatas->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
                                $queryDatas->execute();
    
                                while ($data = $queryDatas->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='index.php?page=meu_horario_escola&data_emissao=" . $data['dt_cadastro'] . "'>" . $data['dt_cadastro'] . "</option>";
                                }
                            ?>
                            </select>
                        </div>
                    </div>
                    <a class="add-user" onclick="document.getElementById('modalCadastro').style.display='block'">
                        <span>Nova grade</span>  
                        <i class='bx bx-plus'></i>
                    </a>
                </div>
            </div>

            <div id="modalCadastro" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="close" onclick="document.getElementById('modalCadastro').style.display='none'">&times;</span>
                <h2 class="modal-title">Cadastro de grade</h2>
                <form action="PAGES/GRADE/addHorario.php" method="post" id="formCadastro">
                    <div class="form-modal" id="formCriarGrade">
                        <input type="hidden" name="id_escola" value="<?php echo $id_escola; ?>">
                        <div class="campo dois">
                            <label for="inicio_semana" class="modal-label">De: </label>
                            <select name="inicio_semana" id="inicio_semana" class="modal-form" required>
                                <option value="">Selecione o dia...</option>
                                <option value="1">Domingo</option>
                                <option value="2">Segunda</option>
                                <option value="3">Terça</option>
                                <option value="4">Quarta</option>
                                <option value="5">Quinta</option>
                                <option value="6">Sexta</option>
                                <option value="7">Sábado</option>
                            </select>
                            <label for="final_semana" class="modal-label">à</label>
                            <select name="final_semana" id="final_semana" class="modal-form" required>
                                <option value="">Selecione o dia...</option>
                                <option value="1">Domingo</option>
                                <option value="2">Segunda</option>
                                <option value="3">Terça</option>
                                <option value="4">Quarta</option>
                                <option value="5">Quinta</option>
                                <option value="6">Sexta</option>
                                <option value="7">Sábado</option>
                            </select>
                        </div>
                        <div class="campo">
                            <label for="carga horária" class="modal-label">Carga horária diária: </label>
                            <input type="number" name="carga_diaria" id="carga_diaria" class="modal-form" max="24" min="3" required>
                        </div>
                        <div class="campo">
                            <label for="horario_inicial" class="modal-label">Horario inicial: </label>
                            <input type="text" name="horario_inicial" id="horario_inicial" class="modal-form" required>
                        </div>
                        <div class="campo">
                            <label for="divisao" class="modal-label">Divisão de tempos (minutos): </label>
                            <input type="number" name="divisao" id="divisao" class="modal-form" required>
                        </div>
                        <div class="campo">
                            <label for="qtd_intervalo" class="modal-label">Quantos intervalos ? </label>
                            <input type="number" name="qtd_intervalo" id="qtd_intervalo" class="modal-form" required>
                        </div>
                        <div id="minutos-intervalo">
                            
                        </div>
                        <div id="intervalo-selects">
                            <!-- Selects gerados dinamicamente -->
                        </div>
                    </div>
                    <input type="submit" class="btn-modal" id="submitBtn" value="Adicionar" disabled style="background-color: grey; cursor: not-allowed;">
                </form>
            </div>
        </div>

            <?php

                // Verificar se há alguma grade inserida
                $sql = "SELECT COUNT(*) AS total FROM grade_horaria_escolar WHERE id_escola = :id_escola";
                $verificar_grade = $conexao->prepare($sql);
                $verificar_grade->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
                $verificar_grade->execute();
                $resultado_grade = $verificar_grade->fetch(PDO::FETCH_ASSOC);

                if ($resultado_grade['total'] > 0):
            ?>
        <div class="content-grade">
            <table class="grade">
                    <tr class="linhas">
                        <td class="tempo dia_semana">Horário</td>
                        <?php
                            $sql = "SELECT MAX(tempo) AS maior_tempo, MIN(dia_semana) AS menor_dia, MAX(dia_semana) AS maior_dia 
                            FROM grade_horaria_escolar 
                            WHERE id_escola = :id_escola";
                            $montar_grade = $conexao->prepare($sql);
                            $montar_grade->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
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
                            if (isset($_GET['data_emissao'])) {
                                $data_emissao = $_GET['data_emissao'];

                                $sql = "SELECT turno, tempo, horario_inicio, horario_final, dia_semana 
                                FROM grade_horaria_escolar 
                                WHERE id_escola = :id_escola AND tempo = :tempo AND dt_cadastro = :dt_cadastro
                                ORDER BY horario_inicio, dia_semana ASC";
                            } else {
                                $sql = "SELECT MAX(dt_cadastro) as mais_recente FROM grade_horaria_escolar WHERE id_escola = :id_escola";
                                $query = $conexao->prepare($sql);
                                $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
                                $query->execute();
                                $data = $query->fetch(PDO::FETCH_ASSOC);
                                $row_data = $data['mais_recente']; 

                                $sql = "SELECT turno, tempo, horario_inicio, horario_final, dia_semana 
                                FROM grade_horaria_escolar 
                                WHERE id_escola = :id_escola AND tempo = :tempo AND dt_cadastro = :dt_cadastro
                                ORDER BY horario_inicio, dia_semana ASC";    
                            }
                            $query = $conexao->prepare($sql);
                            $query->bindValue(':id_escola', $id_escola, PDO::PARAM_INT);
                            $query->bindValue(':tempo', $tempo, PDO::PARAM_INT);
                            if (isset($data_emissao)) {
                                $query->bindValue(':dt_cadastro', $data_emissao);
                            } else {
                                $query->bindValue(':dt_cadastro', $row_data);
                            }
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
                            ?>
                            <td class='tempo' id='tempo_<?php echo $tempo ."_dia_" . $dados['dia_semana']; ?>'></td>
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
    