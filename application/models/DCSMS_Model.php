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

	    public function getStudentGWA($stunum){
			return $query = $this->db->query("SELECT GWA, SchoolYear, Sem
			FROM studentgwa 
			WHERE StuNum LIKE '$stunum'
			ORDER BY SchoolYear, Sem ASC");
		}
	    public function updateRemarks($stunum, $remarks){
			$this->db->query("UPDATE studentinfo
							SET stunote = '$remarks'
							WHERE stunum = '$stunum'");
			$this->db->cache_delete_all();
		}

		public function checkSubjectFailures($stunum, $subject){
			/*$query = $this->db->query("SELECT COUNT(*) AS total FROM `studentgrades`
							WHERE StuNum=$stunum
							AND	StuSubject=\"$subject\"
							AND Grade > 3;");
			$row = $query->row(); 
		 	return $row->total;*/
			$query = $this->db->query("SELECT * FROM `studentgrades`
							WHERE StuNum=$stunum
							AND	StuSubject=\"$subject\"
							AND Grade > 3;");
			return $query->num_rows();
		}

		public function insertIgnore(){
			if($this->db->_error_message()){
		        $sql = $this->db->last_query();
		        $sql = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $sql);
		        $this->db->query($sql);
			}
		}
		public function addDQs($stunum, $DQs){
			$data = array(
				'StuNum' => $stunum,
				'DQDetails' => "$DQs",);
			$insert_query = $this->db->insert('studentdq', $data);
			$insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
			$this->db->query($insert_query);
		}

		/*public function addStudent($stunum, $stuname, $stusubject, $units, $grades, $schoolyear, $sem, $gwa){
			return $this->db->query("INSERT INTO studentgrades
							(StuNum, StuSubject, Units, Grade, SchoolYear, Sem)
							VALUES ($stunum, $stusubject, $units, grades, $schoolyear, $sem");
		}
		public function addStudent($stunum, $stuname, $stusubject, $units, $grades, $schoolyear, $sem, $gwa){
			$data = array(
			   'StuNum' => $stunum,
			   'StuName' => "$stuname" ,
			);
			$data2 = array(
				'StuNum' => $stunum,
				'StuSubject' => "$stusubject",
				'Units' => $units,
				'Grade' => $grades,
				'SchoolYear' => $schoolyear,
				'Sem' => $sem,);
			$data3 = array(
				'StuNum' => $stunum,
				'GWA'=> $gwa,
				'SchoolYear' => $schoolyear,
				'Sem' => $sem,);
			$this->db->insert('studentinfo', $data);
			$this->db->insert('studentgrades', $data2);
			$this->db->insert('studentgwa', $data3);
			$this->insertIgnore();


		/*	$this->db->query("INSERT INTO `studentinfo`
							(StuNum, StuName)
							VALUES ($stunum, \"$stuname\");");
		/*	//$this->db->query("INSERT INTO studentgrades
			//				(StuNum, StuSubject, Units, Grade, SchoolYear, Sem)
			//				VALUES ($stunum, \"$stusubject\", $units, grades, $schoolyear, $sem);");
			return $stunum;
		}
	*/

		public function addStudentInfo($stunum, $stuname, $AH, $MST, $SSP){
			
			$query = $this->db->query("SELECT stunote FROM studentinfo WHERE stunum = $stunum");
			$stunote = "";
			if($query->num_rows() > 0){
				$row = $query->first_row('array');
				$stunote = $row['stunote'];
			}

			$data = array(
			   'StuNum' => $stunum,
			   'StuName' => "$stuname" ,
			   'AH' => $AH,
			   'MST' => $MST,
			   'SSP' => $SSP,
			   'StuNote' => $stunote,
			);

			$this->db->replace('studentinfo', $data);
			return $stunum;
		}

		public function addStudentGrade($stunum, $stusubject, $units, $grades, $schoolyear, $sem){
			$data2 = array(
				'StuNum' => $stunum,
				'StuSubject' => "$stusubject",
				'Units' => $units,
				'Grade' => $grades,
				'SchoolYear' => $schoolyear,
				'Sem' => $sem,);

			$this->db->replace('studentgrades', $data2);
			return $stunum;
		
		}public function addStudentGWA($stunum, $schoolyear, $sem, $gwa){
			$data3 = array(
				'StuNum' => $stunum,
				'GWA'=> $gwa,
				'SchoolYear' => $schoolyear,
				'Sem' => $sem,);

			$this->db->replace('studentgwa', $data3);
			
			return $stunum;
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
				$CSSubjects = array("CS 11", "CS 12", "CS 21" , "CS 22", "CS 24", "CS 30", "CS 32", "CS 70", "CS 80", "CS 115", "CS 120", "CS 130", "CS 131", "CS 133", "CS 134", "CS 135", "CS 137", "CS 140", "CS 145", "CS 150", "CS 153", "CS 155", "CS 160", "CS 165", "CS 171", "CS 172", "CS 173", "CS 174", "CS 175", "CS 176", "CS 180", "CS 191", "CS 192", "CS 194", "CS 195", "CS 196", "CS 197", "CS 198", "CS 199", "CS 200", ); 
				$MathSubjects = array("Math 17", "Math 53", "Math 54", "Math 55");
				$PhysicsSubjects = array("Physics 71", "Physics 72");
				$AHSubjects = array("Araling Kapampangan 10", "Aral Pil 12", "Art Stud 1", "Art Stud 2", "BC 10", "Comm 3", "CW 10", "Eng 1", "Eng 10", "Eng 11", "Eng 12", "Eng 30", "EL 50", "FA 28", "FA 30", "Fil 25", "Fil 40", "Film 10", "Film 12", "Humad 1", "J 18", "Kom 1", "Kom 2", "MPs 10", "MuD 1", "MuL 9", "MuL 13", "Pan Pil 12", "Pan Pil 17", "Pan Pil 19", "Pan Pil 40", "Pan Pil 50", "Theatre 10", "Theatre 11", "Theatre 12");
				$SSPSubjects = array("Anthro 10", "Archaeo 2", "Arkiyoloji 1","Econ 11", "Econ 31", "Geog 1", "Kas 1", "Kas 2", "Lingg 1", "Philo 1", "Philo 10", "Philo 11", "SEA 30", "Soc Sci 1", "Soc Sci 2", "Soc Sci 3","Socio 10");
				$MSTSubjects = array("L Arch 1", "BIO 1", "Chem 1", "EEE 10", "Env Sci 1", "ES 10", "GE 1", "Geol 1", "Math 1", "Math 2", "MBB 1", "MS 1", "Nat Sci 1", "Nat Sci 2", "Physics 10", "STS", "FN 1", "CE 10");
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
			if($searchBy == "Student Number"){
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