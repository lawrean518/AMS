<?php
	class DCSMS_Model extends CI_Model{
		

		function __construct(){
	        // Call the Model constructor
	        parent::__construct();
	    }

	    public function getStudent($stunum){
			
			return $query = $this->db->query("SELECT StuSubject, Grade, Units, Sem, SchoolYear
				FROM studentgrades 		
				WHERE StuNum LIKE '$stunum'
                ORDER BY SchoolYear, Sem, StuSubject ASC");
	    }
	//query that returns a table of student A's grades, subjects, semester, schoolyear, ordered by sem and schoolyear

	    public function updateRemarks($stunum, $remarks){
			$this->db->query("UPDATE studentinfo
							SET stunote = '$remarks'
							WHERE stunum = '$stunum'");
			$this->db->cache_delete_all();
		}

	    public function getDQs($stunum){
			
			return $query = $this->db->query("SELECT DISTINCT DQDetails
				FROM studentdq		
				WHERE StuNum LIKE '$stunum'");
	    }

	    public function exportDBToCSV(){
    		$theFile = fopen("db.csv", "w") or die("Unable to open file!");
    		$query = $this->db->query("SELECT StuNum
										FROM studentinfo");
			foreach ($query->result_array() AS $row){
				$currentStuNum = $row['StuNum'];
				$tableOfGrades =  $this->db->query("SELECT S.StuSubject, S.Grade
													FROM studentgrades S
													WHERE S.StuNum = '$currentStuNum'");
				$CSSubjects = array("CS11", "CS12", "CS30", "CS32", "CS120", "CS130", "CS131", "CS133", "CS135", "CS140", "CS145", "CS150", "CS153", "CS165", "CS180", "CS191", "CS192", "CS194", "CS195", "CS196", "CS198", "CS199", "CS200"); 
				$MathSubjects = array("Math17", "Math53", "Math54", "Math55");
				$PhysicsSubjects = array("Physics71", "Physics72");
				$AHSubjects = array("AH", "AralingKapampangan10", "AralPil12", "ArtStud1", "ArtStud2", "BC10", "Comm3", "CW10", "Eng1", "Eng10", "Eng11", "LArch", "Eng12", "Eng30", "EL50", "FA28", "FA30", "Fil25", "Fil40", "Film10", "Film12", "Humad1", "J18", "Kom1", "Kom2", "MPs10", "MuD1", "MuL9", "MuL13", "PanPil12", "PanPil17", "PanPil19", "PanPil40", "PanPil50", "SEA30", "Theatre10", "Theatre11", "Theatre12");
				$SSPSubjects = array("SSP", "LArch", "Anthro10", "Archaeo2", "Arkiyoloji1","Econ11", "Econ31", "Geog1", "Kas1", "Kas2", "Lingg1", "Philo1", "Philo10", "Philo11", "SEA30", "SocSci1", "SocSci2", "SocSci3","Socio10", "CE10");
				$MSTSubjects = array("MST", "LArch", "Bio1", "Chem1", "EEE10", "EnvSci1", "ES10", "GE1", "Geol1", "Math1", "Math2", "MBB1", "MS1", "NatSci1", "NatSci2", "Physics10", "STS", "FN1", "CE10");
				fwrite($theFile, $currentStuNum);

				//traverse for CS Subjects first
				foreach($tableOfGrades->result_array() AS $rowGrades){
					$subjectTag = substr($rowGrades['StuSubject'], 0, 2);
					$afterSubjectTag = substr($rowGrades['StuSubject'], 2, strlen($rowGrades['StuSubject']));
					if(strcmp($subjectTag, "CS") == 0){
						//echo "PUMASOK";
						fwrite($theFile, "," . $rowGrades["StuSubject"] . " - " . $rowGrades["Grade"]);
						
					}
						
				}

				//traverse for Math Subjects
				foreach($tableOfGrades->result_array() AS $rowGrades){
					$counter = 0;
					while($counter < count($MathSubjects)){
						if(strcmp($rowGrades["StuSubject"], $MathSubjects[$counter]) == 0){
							fwrite($theFile, "," . $rowGrades["StuSubject"] . " - " . $rowGrades["Grade"]);
							//echo "SOMETHING";
							break;
						}
						else{
							$counter++;
						}
					}
				}

				//traverse for Physics Subjects
				foreach($tableOfGrades->result_array() AS $rowGrades){
					$counter = 0;
					while($counter < count($PhysicsSubjects)){
						if(strcmp($rowGrades["StuSubject"], $PhysicsSubjects[$counter]) == 0){
							fwrite($theFile, "," . $rowGrades["StuSubject"] . " - " . $rowGrades["Grade"]);
							//echo "SOMETHING";
							break;
						}
						else{
							$counter++;
						}
					}
				}
		
				//traverse for AH Subjects
				foreach($tableOfGrades->result_array() AS $rowGrades){
					$counter = 0;
					while($counter < count($AHSubjects)){
						if(strcmp($rowGrades["StuSubject"], $AHSubjects[$counter]) == 0){
							fwrite($theFile, "," . $rowGrades["StuSubject"] . " - " . $rowGrades["Grade"]);
							//echo "SOMETHING";
							break;
						}
						else{
							$counter++;
						}
					}
				}
				//traverse for SSP Subjects
				foreach($tableOfGrades->result_array() AS $rowGrades){	
					$counter = 0;
					while($counter < count($SSPSubjects)){
						if(strcmp($rowGrades["StuSubject"], $SSPSubjects[$counter]) == 0){
							fwrite($theFile, "," . $rowGrades["StuSubject"] . " - " . $rowGrades["Grade"]);
							//echo "SOMETHING";
							break;
						}
						else{
							$counter++;
						}
					}
				}		
				//traverse for MST Subjects
				foreach($tableOfGrades->result_array() AS $rowGrades){						
					$counter = 0;
					while($counter < count($MSTSubjects)){
						if(strcmp($rowGrades["StuSubject"], $MSTSubjects[$counter]) == 0){
							fwrite($theFile, "," . $rowGrades["StuSubject"] . " - " . $rowGrades["Grade"]);
							//echo "SOMETHING"; 
							break;
						}
						else{
							$counter++;
						}
					}
				}
				//traverse for OTHERS
				foreach($tableOfGrades->result_array() AS $rowGrades){
					$counter = 0;
					$others = true;
					$superArray = array_merge($MathSubjects, $PhysicsSubjects, $AHSubjects, $MSTSubjects, $SSPSubjects);
					$subjectTag = substr($rowGrades['StuSubject'], 0, 2);
					$afterSubjectTag = substr($rowGrades['StuSubject'], 2, strlen($rowGrades['StuSubject']));
					if(isset($rowGrades['StuSubject'])){
						//echo "pasok1";
						$subjectTag = substr($rowGrades['StuSubject'], 0, 2);
						$afterSubjectTag = substr($rowGrades['StuSubject'], 2, strlen($rowGrades['StuSubject']));
						if(strcmp($subjectTag, "CS") == 0){
							$others =false;
						}
					}	
					while($counter < count($superArray)){
						if(strcmp($rowGrades["StuSubject"], $superArray[$counter]) == 0){
							$others = false;
							break;
						}
						else{
							$counter++;
						}
					}
				
					if($others){
						fwrite($theFile, "," . $rowGrades["StuSubject"] . " - " . $rowGrades["Grade"]);
					}
				}

				fwrite($theFile, "\r\n" );



			}		
	    }


	    public function getStuNameAndNote($stunum){
	    	return $query = $this->db->query("SELECT stuname, stunote
	    		FROM studentinfo
	    		WHERE StuNum = $stunum");
			
	    }
		public function showAllStudents(){
			
   			return $query = $this->db->query('SELECT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
										FROM studentinfo I , (SELECT D.StuNum, gwa
															FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																				FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
	     																							FROM `studentgwa` A
	                        				 														GROUP BY A.StuNum) AS B
											      								WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
	      																		GROUP BY C.StuNum) AS D
															WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,

															(SELECT SI.StuNum, DQTab.DQ
															FROM studentinfo SI, ((SELECT I.stunum, I.stuname, "with DQ" AS DQ
																					FROM studentinfo I JOIN studentdq Q 
																					ON I.stunum = Q.stunum) 
																					UNION
																				(SELECT I.stunum, I.stuname, "without DQ" AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q))) AS DQTab
															WHERE DQTab.Stunum = SI.StuNum) AS DQTable
									WHERE I.stunum = G.stunum AND I.stunum = DQTable.stuNum AND G.stunum = DQTable.stunum;');
			

		}

		public function showAllStudents_sortByAscGWA(){
			return $query = $this->db->query('SELECT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (	SELECT D.StuNum, gwa
																	FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																						FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																										FROM `studentgwa` A
                        				 																	GROUP BY A.StuNum) AS B
      																					WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																					GROUP BY C.StuNum
	 																					) AS D
																	WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,

											(SELECT SI.StuNum, DQTab.DQ

											FROM studentinfo SI, ((SELECT I.stunum, I.stuname, "with DQ" AS DQ
																	FROM studentinfo I JOIN studentdq Q 
																	ON I.stunum = Q.stunum) 
											UNION
											(SELECT I.stunum, I.stuname, "without DQ" AS DQ
											FROM studentinfo I
											WHERE I.stunum 
											NOT IN (SELECT Q.stunum 
													FROM studentdq Q))) AS DQTab
											WHERE DQTab.Stunum = SI.StuNum) AS DQTable
											WHERE I.stunum = G.stunum AND I.stunum = DQTable.stuNum AND G.stunum = DQTable.stunum
											ORDER BY G.gwa ASC;');

		}

		public function showAllStudents_sortByAscGWAWithDQ(){
			return $query = $this->db->query('SELECT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (	SELECT D.StuNum, gwa
																	FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																						FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																										FROM `studentgwa` A
                        				 																	GROUP BY A.StuNum) AS B
      																					WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																					GROUP BY C.StuNum
	 																					) AS D
																	WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,

											(SELECT SI.StuNum, DQTab.DQ

											FROM studentinfo SI, ((SELECT I.stunum, I.stuname, "with DQ" AS DQ
																	FROM studentinfo I JOIN studentdq Q 
																	ON I.stunum = Q.stunum) 
											) AS DQTab
											WHERE DQTab.Stunum = SI.StuNum) AS DQTable
											WHERE I.stunum = G.stunum AND I.stunum = DQTable.stuNum AND G.stunum = DQTable.stunum
											ORDER BY G.gwa ASC;');
			
		}

		public function showAllStudents_sortByAscGWAWithoutDQ(){
			return $query = $this->db->query('SELECT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (	SELECT D.StuNum, gwa
																	FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																						FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																										FROM `studentgwa` A
                        				 																	GROUP BY A.StuNum) AS B
      																					WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																					GROUP BY C.StuNum
	 																					) AS D
																	WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,

											(SELECT SI.StuNum, DQTab.DQ

											FROM studentinfo SI, ((SELECT I.stunum, I.stuname, "without DQ" AS DQ
											FROM studentinfo I
											WHERE I.stunum 
											NOT IN (SELECT Q.stunum 
													FROM studentdq Q))) AS DQTab
											WHERE DQTab.Stunum = SI.StuNum) AS DQTable
											WHERE I.stunum = G.stunum AND I.stunum = DQTable.stuNum AND G.stunum = DQTable.stunum
											ORDER BY G.gwa ASC;');
			
		}

		public function showAllStudents_sortByAscLN(){
			return $query = $this->db->query('SELECT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (	SELECT D.StuNum, gwa
																	FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																						FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																										FROM `studentgwa` A
                        				 																	GROUP BY A.StuNum) AS B
      																					WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																					GROUP BY C.StuNum
	 																					) AS D
																	WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,

											(SELECT SI.StuNum, DQTab.DQ
											FROM studentinfo SI, ((SELECT I.stunum, I.stuname, "with DQ" AS DQ
																	FROM studentinfo I JOIN studentdq Q 
																	ON I.stunum = Q.stunum) 
											UNION
											(SELECT I.stunum, I.stuname, "without DQ" AS DQ
											FROM studentinfo I
											WHERE I.stunum 
											NOT IN (SELECT Q.stunum 
													FROM studentdq Q))) AS DQTab
													WHERE DQTab.Stunum = SI.StuNum) AS DQTable

										WHERE I.stunum = G.stunum AND I.stunum = DQTable.stuNum AND G.stunum = DQTable.stunum
										ORDER BY I.stuname ASC;');
		}

		public function showAllStudents_sortByAscLNWithDQ(){
			return $query = $this->db->query('SELECT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (	SELECT D.StuNum, gwa
																	FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																						FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																										FROM `studentgwa` A
                        				 																	GROUP BY A.StuNum) AS B
      																					WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																					GROUP BY C.StuNum
	 																					) AS D
																	WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,

											(SELECT SI.StuNum, DQTab.DQ
											FROM studentinfo SI, ((SELECT I.stunum, I.stuname, "with DQ" AS DQ
																	FROM studentinfo I JOIN studentdq Q 
																	ON I.stunum = Q.stunum) 
											) AS DQTab
													WHERE DQTab.Stunum = SI.StuNum) AS DQTable

										WHERE I.stunum = G.stunum AND I.stunum = DQTable.stuNum AND G.stunum = DQTable.stunum
										ORDER BY I.stuname ASC;');
			
		}

		public function showAllStudents_sortByAscLNWithoutDQ(){
			return $query = $this->db->query('SELECT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (	SELECT D.StuNum, gwa
																	FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																						FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																										FROM `studentgwa` A
                        				 																	GROUP BY A.StuNum) AS B
      																					WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																					GROUP BY C.StuNum
	 																					) AS D
																	WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,

											(SELECT SI.StuNum, DQTab.DQ
											FROM studentinfo SI, ((SELECT I.stunum, I.stuname, "without DQ" AS DQ
											FROM studentinfo I
											WHERE I.stunum 
											NOT IN (SELECT Q.stunum 
													FROM studentdq Q))) AS DQTab
													WHERE DQTab.Stunum = SI.StuNum) AS DQTable

										WHERE I.stunum = G.stunum AND I.stunum = DQTable.stuNum AND G.stunum = DQTable.stunum
										ORDER BY I.stuname ASC;');	
		}

		public function showAllStudents_sortByAscSN(){
			return $query = $this->db->query('SELECT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (	SELECT D.StuNum, gwa
																	FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																						FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																										FROM `studentgwa` A
                        				 																	GROUP BY A.StuNum) AS B
      																					WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																					GROUP BY C.StuNum
	 																					) AS D
																	WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,

																(SELECT SI.StuNum, DQTab.DQ
																FROM	studentinfo SI, ((SELECT I.stunum, I.stuname, "with DQ" AS DQ
																							FROM studentinfo I JOIN studentdq Q 
																							ON I.stunum = Q.stunum) 
																UNION
																(SELECT I.stunum, I.stuname, "without DQ" AS DQ
																FROM studentinfo I
																WHERE I.stunum 
																NOT IN (SELECT Q.stunum 
																		FROM studentdq Q))) AS DQTab
																		WHERE DQTab.Stunum = SI.StuNum) AS DQTable

											WHERE I.stunum = G.stunum AND I.stunum = DQTable.stuNum AND G.stunum = DQTable.stunum
											ORDER BY I.stunum ASC;');
		}

		public function showAllStudents_sortByAscSNWithDQ(){
			return $query = $this->db->query('SELECT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (	SELECT D.StuNum, gwa
																	FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																						FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																										FROM `studentgwa` A
                        				 																	GROUP BY A.StuNum) AS B
      																					WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																					GROUP BY C.StuNum
	 																					) AS D
																	WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,

																(SELECT SI.StuNum, DQTab.DQ
																FROM	studentinfo SI, ((SELECT I.stunum, I.stuname, "with DQ" AS DQ
																							FROM studentinfo I JOIN studentdq Q 
																							ON I.stunum = Q.stunum) 
																) AS DQTab
																		WHERE DQTab.Stunum = SI.StuNum) AS DQTable

											WHERE I.stunum = G.stunum AND I.stunum = DQTable.stuNum AND G.stunum = DQTable.stunum
											ORDER BY I.stunum ASC;');
			
		}

		public function showAllStudents_sortByAscSNWithoutDQ(){
			return $query = $this->db->query('SELECT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (	SELECT D.StuNum, gwa
																	FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																						FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																										FROM `studentgwa` A
                        				 																	GROUP BY A.StuNum) AS B
      																					WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																					GROUP BY C.StuNum
	 																					) AS D
																	WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,

																(SELECT SI.StuNum, DQTab.DQ
																FROM	studentinfo SI, ((SELECT I.stunum, I.stuname, "without DQ" AS DQ
																FROM studentinfo I
																WHERE I.stunum 
																NOT IN (SELECT Q.stunum 
																		FROM studentdq Q))) AS DQTab
																		WHERE DQTab.Stunum = SI.StuNum) AS DQTable

											WHERE I.stunum = G.stunum AND I.stunum = DQTable.stuNum AND G.stunum = DQTable.stunum
											ORDER BY I.stunum ASC;');
			
		}

		public function showAllStudents_sortByDescGWA(){
			return $query = $this->db->query('SELECT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (	SELECT D.StuNum, gwa
																	FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																						FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																										FROM `studentgwa` A
                        				 																	GROUP BY A.StuNum) AS B
      																					WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																					GROUP BY C.StuNum
	 																					) AS D
																	WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,

																	(SELECT SI.StuNum, DQTab.DQ
																	FROM	studentinfo SI, ((SELECT I.stunum, I.stuname, "with DQ" AS DQ
																							FROM studentinfo I JOIN studentdq Q 
																							ON I.stunum = Q.stunum) 	
												UNION
												(SELECT I.stunum, I.stuname, "without DQ" AS DQ
												FROM studentinfo I
												WHERE I.stunum 
												NOT IN (SELECT Q.stunum 
														FROM studentdq Q))) AS DQTab
												WHERE DQTab.Stunum = SI.StuNum) AS DQTable
												WHERE I.stunum = G.stunum AND I.stunum = DQTable.stuNum AND G.stunum = DQTable.stunum
												ORDER BY G.gwa DESC;');
		}

		public function showAllStudents_sortByDescGWAWithDQ(){
			return $query = $this->db->query('SELECT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (	SELECT D.StuNum, gwa
																	FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																						FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																										FROM `studentgwa` A
                        				 																	GROUP BY A.StuNum) AS B
      																					WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																					GROUP BY C.StuNum
	 																					) AS D
																	WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,

																	(SELECT SI.StuNum, DQTab.DQ
																	FROM	studentinfo SI, ((SELECT I.stunum, I.stuname, "with DQ" AS DQ
																							FROM studentinfo I JOIN studentdq Q 
																							ON I.stunum = Q.stunum) 	
												) AS DQTab
												WHERE DQTab.Stunum = SI.StuNum) AS DQTable
												WHERE I.stunum = G.stunum AND I.stunum = DQTable.stuNum AND G.stunum = DQTable.stunum
												ORDER BY G.gwa DESC;');
			
		}

		public function showAllStudents_sortByDescGWAWithoutDQ(){
			return $query = $this->db->query('SELECT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (	SELECT D.StuNum, gwa
																	FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																						FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																										FROM `studentgwa` A
                        				 																	GROUP BY A.StuNum) AS B
      																					WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																					GROUP BY C.StuNum
	 																					) AS D
																	WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,

																	(SELECT SI.StuNum, DQTab.DQ
																	FROM	studentinfo SI, (
												(SELECT I.stunum, I.stuname, "without DQ" AS DQ
												FROM studentinfo I
												WHERE I.stunum 
												NOT IN (SELECT Q.stunum 
														FROM studentdq Q))) AS DQTab
												WHERE DQTab.Stunum = SI.StuNum) AS DQTable
												WHERE I.stunum = G.stunum AND I.stunum = DQTable.stuNum AND G.stunum = DQTable.stunum
												ORDER BY G.gwa DESC;');
			
		}

		public function showAllStudents_sortByDescLN(){
			return $query = $this->db->query('SELECT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (	SELECT D.StuNum, gwa
																	FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																						FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																										FROM `studentgwa` A
                        				 																	GROUP BY A.StuNum) AS B
      																					WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																					GROUP BY C.StuNum
	 																					) AS D
																	WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,

																	(SELECT SI.StuNum, DQTab.DQ

											FROM studentinfo SI, ((SELECT I.stunum, I.stuname, "with DQ" AS DQ
											FROM studentinfo I JOIN studentdq Q 
											ON I.stunum = Q.stunum) 
											UNION
												(SELECT I.stunum, I.stuname, "without DQ" AS DQ
												FROM studentinfo I
												WHERE I.stunum 
												NOT IN (SELECT Q.stunum 
														FROM studentdq Q))) AS DQTab
														WHERE DQTab.Stunum = SI.StuNum) AS DQTable
											WHERE I.stunum = G.stunum AND I.stunum = DQTable.stuNum AND G.stunum = DQTable.stunum
											ORDER BY I.stuname DESC;');
		}

		public function showAllStudents_sortByDescLNWithDQ(){
			return $query = $this->db->query('SELECT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (	SELECT D.StuNum, gwa
																	FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																						FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																										FROM `studentgwa` A
                        				 																	GROUP BY A.StuNum) AS B
      																					WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																					GROUP BY C.StuNum
	 																					) AS D
																	WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,

																	(SELECT SI.StuNum, DQTab.DQ

											FROM studentinfo SI, ((SELECT I.stunum, I.stuname, "with DQ" AS DQ
											FROM studentinfo I JOIN studentdq Q 
											ON I.stunum = Q.stunum) 
											) AS DQTab
														WHERE DQTab.Stunum = SI.StuNum) AS DQTable
											WHERE I.stunum = G.stunum AND I.stunum = DQTable.stuNum AND G.stunum = DQTable.stunum
											ORDER BY I.stuname DESC;');
			
		}

		public function showAllStudents_sortByDescLNWithoutDQ(){
			return $query = $this->db->query('SELECT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (	SELECT D.StuNum, gwa
																	FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																						FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																										FROM `studentgwa` A
                        				 																	GROUP BY A.StuNum) AS B
      																					WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																					GROUP BY C.StuNum
	 																					) AS D
																	WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,

																	(SELECT SI.StuNum, DQTab.DQ

											FROM studentinfo SI, (
												(SELECT I.stunum, I.stuname, "without DQ" AS DQ
												FROM studentinfo I
												WHERE I.stunum 
												NOT IN (SELECT Q.stunum 
														FROM studentdq Q))) AS DQTab
														WHERE DQTab.Stunum = SI.StuNum) AS DQTable
											WHERE I.stunum = G.stunum AND I.stunum = DQTable.stuNum AND G.stunum = DQTable.stunum
											ORDER BY I.stuname DESC;');
			
		}

		public function showAllStudents_sortByDescSN(){
			return $query = $this->db->query('SELECT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, 
																(SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																				FROM `studentgwa` A
                        				 											GROUP BY A.StuNum) AS B
      															WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      															GROUP BY C.StuNum
	 															) AS D
											WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,

											(SELECT SI.StuNum, DQTab.DQ
											FROM studentinfo SI, ((SELECT I.stunum, I.stuname, "with DQ" AS DQ
																	FROM studentinfo I JOIN studentdq Q 
																	ON I.stunum = Q.stunum) 
											UNION
											(SELECT I.stunum, I.stuname, "without DQ" AS DQ
											FROM studentinfo I
											WHERE I.stunum 
											NOT IN (SELECT Q.stunum 
													FROM studentdq Q))) AS DQTab
													WHERE DQTab.Stunum = SI.StuNum) AS DQTable

											WHERE I.stunum = G.stunum AND I.stunum = DQTable.stuNum AND G.stunum = DQTable.stunum
											ORDER BY I.stunum DESC;');
		}

		public function showAllStudents_sortByDescSNWithDQ(){
			return $query = $this->db->query('SELECT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, 
																(SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																				FROM `studentgwa` A
                        				 											GROUP BY A.StuNum) AS B
      															WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      															GROUP BY C.StuNum
	 															) AS D
											WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,

											(SELECT SI.StuNum, DQTab.DQ
											FROM studentinfo SI, ((SELECT I.stunum, I.stuname, "with DQ" AS DQ
																	FROM studentinfo I JOIN studentdq Q 
																	ON I.stunum = Q.stunum) 
											) AS DQTab
													WHERE DQTab.Stunum = SI.StuNum) AS DQTable

											WHERE I.stunum = G.stunum AND I.stunum = DQTable.stuNum AND G.stunum = DQTable.stunum
											ORDER BY I.stunum DESC;');
			
		}

		public function showAllStudents_sortByDescSNWithoutDQ(){
			return $query = $this->db->query('SELECT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, 
																(SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																				FROM `studentgwa` A
                        				 											GROUP BY A.StuNum) AS B
      															WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      															GROUP BY C.StuNum
	 															) AS D
											WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,

											(SELECT SI.StuNum, DQTab.DQ
											FROM studentinfo SI, (
											(SELECT I.stunum, I.stuname, "without DQ" AS DQ
											FROM studentinfo I
											WHERE I.stunum 
											NOT IN (SELECT Q.stunum 
													FROM studentdq Q))) AS DQTab
													WHERE DQTab.Stunum = SI.StuNum) AS DQTable

											WHERE I.stunum = G.stunum AND I.stunum = DQTable.stuNum AND G.stunum = DQTable.stunum
											ORDER BY I.stunum DESC;');

		}
		
		public function showAllStudents_WithDQ(){
			return $query = $this->db->query('SELECT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
												FROM studentinfo I , (	SELECT D.StuNum, gwa
																		FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																							FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																						FROM `studentgwa` A
                        				 													GROUP BY A.StuNum) AS B
      																	WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																	GROUP BY C.StuNum
	 																	) AS D
												WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,

											(SELECT SI.StuNum, DQTab.DQ
											FROM	studentinfo SI, ((SELECT I.stunum, I.stuname, "with DQ" AS DQ
																		FROM studentinfo I JOIN studentdq Q 
																		ON I.stunum = Q.stunum) 
																	) AS DQTab
											WHERE DQTab.Stunum = SI.StuNum) AS DQTable
											WHERE I.stunum = G.stunum AND I.stunum = DQTable.stuNum AND G.stunum = DQTable.stunum;
											');
		}

		public function showAllStudents_WithoutDQ(){
			return $query = $this->db->query('SELECT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (	SELECT D.StuNum, gwa
																	FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																						FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																										FROM `studentgwa` A
                        				 																	GROUP BY A.StuNum) AS B
      																WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																GROUP BY C.StuNum
	 																) AS D
												WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,

											(SELECT SI.StuNum, DQTab.DQ
											FROM	studentinfo SI, ((SELECT I.stunum, I.stuname, "without DQ" AS DQ
																	FROM studentinfo I
																	WHERE I.stunum 
																	NOT IN (SELECT Q.stunum 
																			FROM studentdq Q))) AS DQTab
												WHERE DQTab.Stunum = SI.StuNum) AS DQTable
												WHERE I.stunum = G.stunum AND I.stunum = DQTable.stuNum AND G.stunum = DQTable.stunum;');
		}



//==========================================================================================================================


		public function showSearchQuery($searchString, $searchBy){
			if($searchBy == "stuNum"){
				 $query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM studentgwa A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, ((SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) 
																						UNION
																					(SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q))) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stunum LIKE '$searchString%' AND DQTable.stunum = I.stunum");
				
			}
			else{
				//echo "hello";
				$query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM `studentgwa` A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, ((SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) 
																						UNION
																					(SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q))) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stuname LIKE '$searchString%' AND DQTable.stunum = I.stunum");	

			}

			return $query;
		}
	

		public function showSearchQuery_sortByWithDQ($searchString, $searchBy){
			if($searchBy == "stuNum"){
				 $query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM studentgwa A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stunum LIKE '$searchString%' AND DQTable.stunum = I.stunum");
				
			}
			else{
				//echo "hello";
				$query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM `studentgwa` A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stuname LIKE '$searchString%' AND DQTable.stunum = I.stunum");	

			}

			return $query;
		}

		public function showSearchQuery_sortByWithoutDQ($searchString, $searchBy){
			if($searchBy == "stuNum"){
				 $query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM studentgwa A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q)) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stunum LIKE '$searchString%' AND DQTable.stunum = I.stunum");
				
			}
			else{
				//echo "hello";
				$query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM `studentgwa` A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q)) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stuname LIKE '$searchString%' AND DQTable.stunum = I.stunum");	

			}

			return $query;
		}

		public function showSearchQuery_sortByAscGWA($searchString, $searchBy){
			if($searchBy == "stuNum"){
				 $query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM studentgwa A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, ((SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) 
																						UNION
																					(SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q))) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stunum LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY G.gwa ASC");
				
			}
			else{
				//echo "hello";
				$query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM `studentgwa` A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, ((SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) 
																						UNION
																					(SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q))) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stuname LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY G.gwa ASC");	

			}

			return $query;
		}

		public function showSearchQuery_sortByAscGWAWithDQ($searchString, $searchBy){
			if($searchBy == "stuNum"){
				 $query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM studentgwa A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stunum LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY G.gwa ASC");
				
			}
			else{
				//echo "hello";
				$query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM `studentgwa` A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stuname LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY G.gwa ASC");	

			}

			return $query;
		}


		public function showSearchQuery_sortByAscGWAWithoutDQ($searchString, $searchBy){
			if($searchBy == "stuNum"){
				 $query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM studentgwa A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q)) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stunum LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY G.gwa ASC");
				
			}
			else{
				//echo "hello";
				$query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM `studentgwa` A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q)) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stuname LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY G.gwa ASC");	

			}

			return $query;
		}


		public function showSearchQuery_sortByAscLN($searchString, $searchBy){
			if($searchBy == "stuNum"){
				 $query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM studentgwa A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, ((SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) 
																						UNION
																					(SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q))) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stunum LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname ASC");
				
			}
			else{
				//echo "hello";
				$query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM `studentgwa` A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, ((SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) 
																						UNION
																					(SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q))) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stuname LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname ASC");	

			}

			return $query;
		}


		public function showSearchQuery_sortByAscLNWithDQ($searchString, $searchBy){
			if($searchBy == "stuNum"){
				 $query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM studentgwa A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stunum LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname ASC");
				
			}
			else{
				//echo "hello";
				$query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM `studentgwa` A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stuname LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname ASC");	

			}

			return $query;
		}


		public function showSearchQuery_sortByAscLNWithoutDQ($searchString, $searchBy){
			if($searchBy == "stuNum"){
				 $query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM studentgwa A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q)) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stunum LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname ASC");
				
			}
			else{
				//echo "hello";
				$query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM `studentgwa` A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q)) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stuname LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname ASC");	

			}

			return $query;
		}




		public function showSearchQuery_sortByAscSN($searchString, $searchBy){
			if($searchBy == "stuNum"){
				 $query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM studentgwa A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, ((SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) 
																						UNION
																					(SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q))) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stunum LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname ASC");
				
			}
			else{
				//echo "hello";
				$query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM `studentgwa` A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, ((SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) 
																						UNION
																					(SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q))) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stuname LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname ASC");	

			}

			return $query;
		}


		public function showSearchQuery_sortByAscSNWithDQ($searchString, $searchBy){
			if($searchBy == "stuNum"){
				 $query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM studentgwa A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stunum LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname ASC");
				
			}
			else{
				//echo "hello";
				$query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM `studentgwa` A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stuname LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname ASC");	

			}

			return $query;
		}

		public function showSearchQuery_sortByAscSNWithoutDQ($searchString, $searchBy){
			if($searchBy == "stuNum"){
				 $query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM studentgwa A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q)) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stunum LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname ASC");
				
			}
			else{
				//echo "hello";
				$query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM `studentgwa` A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q)) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stuname LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname ASC");	

			}

			return $query;
		}

		public function showSearchQuery_sortByDescGWA($searchString, $searchBy){
			if($searchBy == "stuNum"){
				 $query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM studentgwa A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, ((SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) 
																						UNION
																					(SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q))) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stunum LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY G.gwa DESC");
				
			}
			else{
				//echo "hello";
				$query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM `studentgwa` A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, ((SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) 
																						UNION
																					(SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q))) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stuname LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY G.gwa DESC");	

			}

			return $query;
		}

		public function showSearchQuery_sortByDescGWAWithDQ($searchString, $searchBy){
			if($searchBy == "stuNum"){
				 $query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM studentgwa A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stunum LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY G.gwa DESC");
				
			}
			else{
				//echo "hello";
				$query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM `studentgwa` A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stuname LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY G.gwa DESC");	

			}

			return $query;
		}

		public function showSearchQuery_sortByDescGWAWithoutDQ($searchString, $searchBy){
			if($searchBy == "stuNum"){
				 $query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM studentgwa A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q)) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stunum LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY G.gwa DESC");
				
			}
			else{
				//echo "hello";
				$query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM `studentgwa` A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q)) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stuname LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY G.gwa DESC");	

			}

			return $query;
		}

		public function showSearchQuery_sortByDescLN($searchString, $searchBy){
			if($searchBy == "stuNum"){
				 $query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM studentgwa A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, ((SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) 
																						UNION
																					(SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q))) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stunum LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname DESC");
				
			}
			else{
				//echo "hello";
				$query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM `studentgwa` A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, ((SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) 
																						UNION
																					(SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q))) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stuname LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname DESC");	

			}

			return $query;
		}

		public function showSearchQuery_sortByDescLNWithDQ($searchString, $searchBy){
			if($searchBy == "stuNum"){
				 $query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM studentgwa A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stunum LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname DESC");
				
			}
			else{
				//echo "hello";
				$query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM `studentgwa` A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stuname LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname DESC");	

			}

			return $query;
		}


		public function showSearchQuery_sortByDescLNWithoutDQ($searchString, $searchBy){
			if($searchBy == "stuNum"){
				 $query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM studentgwa A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q)) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stunum LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname DESC");
				
			}
			else{
				//echo "hello";
				$query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM `studentgwa` A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q)) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stuname LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname DESC");	

			}

			return $query;
		}

		public function showSearchQuery_sortByDescSN($searchString, $searchBy){
			if($searchBy == "stuNum"){
				 $query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM studentgwa A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, ((SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) 
																						UNION
																					(SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q))) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stunum LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname DESC");
				
			}
			else{
				//echo "hello";
				$query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM `studentgwa` A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, ((SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) 
																						UNION
																					(SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q))) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stuname LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname DESC");	

			}

			return $query;
		}


		public function showSearchQuery_sortByDescSNWithDQ($searchString, $searchBy){
			if($searchBy == "stuNum"){
				 $query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM studentgwa A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stunum LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname DESC");
				
			}
			else{
				//echo "hello";
				$query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM `studentgwa` A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'with DQ' AS DQ
																						FROM studentinfo I JOIN studentdq Q 
																						ON I.stunum = Q.stunum) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stuname LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname DESC");	

			}

			return $query;
		}

		public function showSearchQuery_sortByDescSNWithoutDQ($searchString, $searchBy){
			if($searchBy == "stuNum"){
				 $query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM studentgwa A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q)) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stunum LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname DESC");
				
			}
			else{
				//echo "hello";
				$query = $this->db->query("SELECT DISTINCT I.stunum, I.stuname, G.gwa, AH, SSP, MST, DQTable.DQ, I.stunote
											FROM studentinfo I , (SELECT D.StuNum, gwa
																FROM studentgwa E, (SELECT C.Stunum, MAX(sem) AS Sem, C.SchoolYear
																					FROM studentgwa C, (SELECT A.stunum, MAX(SchoolYear) AS SchoolYear
     																									FROM `studentgwa` A
                        				 																GROUP BY A.StuNum) AS B
      																				WHERE C.StuNum = B.StuNum AND C.SchoolYear = B.SchoolYear
      																				GROUP BY C.StuNum) AS D
																WHERE  D.StuNum = E.StuNum AND E.SchoolYear = D.SchoolYear AND E.Sem = D.Sem) AS G,
																(SELECT SI.StuNum, DQTab.DQ
																FROM studentinfo SI, (SELECT I.stunum, I.stuname, 'without DQ' AS DQ
																					FROM studentinfo I
																					WHERE I.stunum 
																					NOT IN (SELECT Q.stunum 
																							FROM studentdq Q)) AS DQTab
																WHERE DQTab.Stunum = SI.StuNum) AS DQTable								
											WHERE I.stunum = G.stunum AND I.stuname LIKE '$searchString%' AND DQTable.stunum = I.stunum
											ORDER BY I.stuname DESC");	

			}

			return $query;
		}
			
	}


?>