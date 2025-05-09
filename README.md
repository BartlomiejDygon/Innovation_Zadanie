Instalation : 
1. Dwonload form prepository from git.
2. Use "composer install"
3. Add DATABASE_URL to .env and created database "php bin/console doctrine:database:create"
4. Perform migrations "php bin/console doctrine:migrations:migrate"
6. Done :)

Api endopoints:
1. For created User "/api/user/add"

   A. The data it accepts :
	   {
		   firstName: string,
		   lastName: string
	   }
   
	B. Return data :
		{
			userID: uuid
   		}

	C. Usage example :

    	curl -X POST http://localhost:8000/api/user/add \
    	-H "Content-Type: application/json" \
    	-d '{"firstName": "Janush", "lastName": "Programowania"}'
   
2. For created working time "/api/working-time/add"
	
 	A. The data it accepts :
		{
			userId: string,
			startingWork: string,
			endingWork: string
		}
	B. Return data :
		{
		succes message.
		}   

	C. Usage example :

		curl -X POST http://localhost:8000/api/working-time/add \
		-H "Content-Type: application/json" \
		-d '{
		"userId": "8ee8bc04-5949-4426-8432-eb079bda8a0b",
		"startingWork": "2025-05-07 10:00:00",
		"endingWork": "2025-05-07 18:25:00"
		}'

3. For get working time summary "/api/working-time-summary/get"
	
 	A. The data it accepts :
		{
			userId: string,
			date: date format "YYYY-MM-DD" or "YYYY-MM"
		}

	B. Return data :
		{
			array
		}  

	C. Usage example :

		curl -X POST http://localhost:8000/api/working-time-summary/get \
		-H "Content-Type: application/json" \
		-d '{
		"userId": "8ee8bc04-5949-4426-8432-eb079bda8a0b",
		"date": "2025-05-07"
		}'

   


