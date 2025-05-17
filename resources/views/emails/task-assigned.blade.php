<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Você foi atribuído a uma tarefa</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f6f9fc; margin: 0; padding: 0;">
    <table width="100%" style="padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" style="background-color: #ffffff; border-radius: 8px; padding: 30px;">
                    <tr>
                        <td style="text-align: center;">
                            <h2 style="color: #2d3748;">Você foi atribuído a uma nova tarefa 🎯</h2>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>Olá,</p>
                            <p>Você foi designado para a seguinte tarefa:</p>

                            <ul style="line-height: 1.6;">
                                <li><strong>Projeto:</strong> {{ $task['projectName'] ?? 'N/A' }}</li>
                                <li><strong>Nome da Tarefa:</strong> {{ $task['name'] }}</li>
                                <li><strong>Status:</strong> {{ $task['status']['name'] }}</li>
                                <li><strong>Descrição:</strong> {{ $task['description'] ?? 'Sem descrição' }}</li>
                            </ul>

                            <p style="margin-top: 40px; color: #718096; font-size: 12px;">Este é um e-mail automático, por favor não responda.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
