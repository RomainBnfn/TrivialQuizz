$result = ($bdd -> query("SELECT COUNT(que_id) as nbOccurrence FROM quiz_quest WHERE que_id = $id_question")) -> fetch();
$nbOccurence = $result["nbOccurrence"];
if($nbOccurence > 1)// La question est présente dans plusieurs quizzes, on supprime juste le quiz-quest
{
  $bdd -> query("DELETE FROM quiz_quest WHERE que_id = $id_question AND qui_id = $id_quizz");
}
else { //La question est présente un seul quizz : on la supprime
   
