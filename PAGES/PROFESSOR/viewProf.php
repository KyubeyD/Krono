<?php
require_once __DIR__ . '/../../ASSETS/conexao.php';

if (isset($_GET['id_professor'])) {
    $id_professor = $_GET['id_professor'];

    // Consulta para obter os detalhes do professor
    $sql = "SELECT * FROM professor WHERE id_professor = :id_professor";
    $query = $conexao->prepare($sql);
    $query->bindValue(':id_professor', $id_professor, PDO::PARAM_INT);
    $query->execute();

    if ($query->rowCount() > 0) {
        $professor = $query->fetch(PDO::FETCH_ASSOC);
        echo "<h2>Detalhes do Professor</h2>";
        echo "<p><strong>Nome:</strong> " . htmlspecialchars($professor['nome_professor']) . "</p>";
        echo "<p><strong>E-mail:</strong> " . htmlspecialchars($professor['email_professor']) . "</p>";
        echo "<p><strong>Telefone:</strong> " . htmlspecialchars($professor['telefone_professor']) . "</p>";
        echo "<p><strong>CPF:</strong> " . htmlspecialchars($professor['cpf_professor']) . "</p>";

        // Consulta para obter os vínculos do professor com as escolas
        $sqlVinculosEscola = "SELECT e.nome_escola FROM vinculo_prof_esc v 
                              JOIN escola e ON v.id_escola = e.id_escola 
                              WHERE v.id_professor = :id_professor";
        $queryVinculosEscola = $conexao->prepare($sqlVinculosEscola);
        $queryVinculosEscola->bindValue(':id_professor', $id_professor, PDO::PARAM_INT);
        $queryVinculosEscola->execute();

        if ($queryVinculosEscola->rowCount() > 0) {
            echo "<h3>Vínculos com Escolas:</h3>";
            echo "<ul>";
            while ($vinculo = $queryVinculosEscola->fetch(PDO::FETCH_ASSOC)) {
                echo "<li>" . htmlspecialchars($vinculo['nome_escola']) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Este professor não está vinculado a nenhuma escola.</p>";
        }

        // Consulta para obter os vínculos do professor com as disciplinas
        $sqlVinculosDisciplina = "SELECT d.nome_disciplina FROM vinculo_prof_disc v 
                                   JOIN disciplina d ON v.id_disciplina = d.id_disciplina 
                                   WHERE v.id_professor = :id_professor";
        $queryVinculosDisciplina = $conexao->prepare($sqlVinculosDisciplina);
        $queryVinculosDisciplina->bindValue(':id_professor', $id_professor, PDO::PARAM_INT);
        $queryVinculosDisciplina->execute();

        if ($queryVinculosDisciplina->rowCount() > 0) {
            echo "<h3>Vínculos com Disciplinas:</h3>";
            echo "<ul>";
            while ($vinculo = $queryVinculosDisciplina->fetch(PDO::FETCH_ASSOC)) {
                echo "<li>" . htmlspecialchars($vinculo['nome_disciplina']) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Este professor não está vinculado a nenhuma disciplina.</p>";
        }

        // Aqui você pode adicionar mais consultas para outros vínculos se necessário

    } else {
        echo "<p>Professor não encontrado.</p>";
    }
} else {
    echo "<p>ID do professor não especificado.</p>";
}
