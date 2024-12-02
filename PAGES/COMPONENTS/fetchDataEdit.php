<?php
require_once __DIR__ . '/../../ASSETS/conexao.php';

if (isset($_GET['tipo']) && isset($_GET['id'])) {
    $tipo = $_GET['tipo'];
    $id = (int)$_GET['id'];

    try {
        $query = "";
        switch ($tipo) {
            case "regra_disp":
                $query = "SELECT nome_regra, descricao, importante FROM regra_disp WHERE id_regra = :id";
                break;

            case "carga_horaria_esc":
                $query = "SELECT horas_semanais FROM carga_horaria_esc WHERE id_carga_horaria_esc = :id";
                break;
            case "curso":
                $query = "SELECT nome_curso, sigla_curso FROM curso WHERE id_curso = :id";
                break;
            case "disciplina":
                $query = "
                    SELECT 
                        nome_disciplina, 
                        sigla_disciplina, 
                        carga_horaria 
                    FROM disciplina 
                    WHERE id_disciplina = :id
                ";
                break;     
                case "turma":
                    // Consulta para obter os dados da turma
                    $query = "
                        SELECT t.nome_turma, t.serie_turma, e.nome_escola, c.nome_curso 
                        FROM turma t
                        INNER JOIN escola e ON t.id_escola = e.id_escola
                        INNER JOIN curso c ON t.id_curso = c.id_curso
                        WHERE t.id_turma = :id
                    ";
                    break;   
            default:
                throw new Exception("Tipo inválido");
        }

        $stmt = $conexao->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($tipo === "disciplina") {
            // Busca todos os cursos disponíveis
            $queryTodosCursos = "SELECT id_curso, nome_curso FROM curso";
            $stmtTodosCursos = $conexao->prepare($queryTodosCursos);
            $stmtTodosCursos->execute();
            $todosCursos = $stmtTodosCursos->fetchAll(PDO::FETCH_ASSOC);

            // Busca os cursos vinculados à disciplina
            $queryCursosVinculados = "
                SELECT id_curso 
                FROM vinculo_curso_disc 
                WHERE id_disciplina = :id
            ";
            $stmtCursosVinculados = $conexao->prepare($queryCursosVinculados);
            $stmtCursosVinculados->bindValue(':id', $id, PDO::PARAM_INT);
            $stmtCursosVinculados->execute();
            $cursosVinculados = $stmtCursosVinculados->fetchAll(PDO::FETCH_COLUMN);

            // Marca os cursos vinculados como checados
            foreach ($todosCursos as &$curso) {
                $curso['checado'] = in_array($curso['id_curso'], $cursosVinculados);
            }

            // Adiciona os cursos ao resultado final
            $data['cursos'] = $todosCursos;
        }


        if ($data) {
            echo json_encode($data);
        } else {
            throw new Exception("Nenhum dado encontrado");
        }
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Parâmetros inválidos']);
}
