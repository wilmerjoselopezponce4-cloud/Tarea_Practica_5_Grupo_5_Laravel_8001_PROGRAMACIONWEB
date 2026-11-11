<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{User,Role,Book};
class DatabaseSeeder extends Seeder{
 public function run():void{
  foreach([1=>'Administrador',2=>'Bibliotecario',3=>'Lector'] as $id=>$name){Role::updateOrCreate(['id'=>$id],['name'=>$name]);}
  $users=[
    ['Jorge Toapanta','jorge.toapanta@grupo5.test',1],
    ['Wilmer Adriano','wilmer.adriano@grupo5.test',2],
    ['Jhoely Dávila','jhoely.davila@grupo5.test',2],
    ['Jhonatan Chávez','jhonatan.chavez@grupo5.test',3],
    ['Wilmer López','wilmer.lopez@grupo5.test',3],
  ];
  foreach($users as [$name,$mail,$role])User::firstOrCreate(['email'=>$mail],[
      'username'=>$name,'role_id'=>$role,'password'=>Hash::make('12345678910'),'is_active'=>true]);
  $books=[
    ['Cien años de soledad','Gabriel García Márquez',1967,'Novela',5,'Una historia de generaciones, memoria y destino en Macondo.','9780307474728',1],
    ['El principito','Antoine de Saint-Exupéry',1943,'Ficción',7,'Un viaje sobre la amistad, las preguntas y los vínculos humanos.','9780156012195',2],
    ['Don Quijote de la Mancha','Miguel de Cervantes',1605,'Clásicos',4,'Las aventuras de Alonso Quijano y Sancho Panza.','9788424117286',3],
    ['La casa de los espíritus','Isabel Allende',1982,'Novela',3,'Saga familiar entre recuerdos y transformaciones sociales.','9788401346651',4],
    ['Rayuela','Julio Cortázar',1963,'Clásicos',3,'Una novela de lectura abierta y múltiples recorridos.','9788437604571',5],
    ['1984','George Orwell',1949,'Ficción',6,'Ficción distópica sobre vigilancia, lenguaje y poder.','9780451524935',6],
    ['Pedro Páramo','Juan Rulfo',1955,'Clásicos',4,'Un viaje al pueblo de Comala entre voces y recuerdos.','9788437604182',2],
    ['La tregua','Mario Benedetti',1960,'Novela',5,'Diario íntimo de un oficinista y sus cambios cotidianos.','9788420636958',3],
    ['El túnel','Ernesto Sabato',1948,'Ficción',3,'Novela psicológica narrada desde la obsesión.','9788432212535',5],
    ['Sapiens','Yuval Noah Harari',2011,'Historia',4,'Una panorámica histórica de las sociedades humanas.','9780062316110',1],
    ['Breve historia del tiempo','Stephen Hawking',1988,'Ciencia',5,'Ideas de cosmología explicadas a lectores generales.','9780553380163',4],
    ['Hábitos atómicos','James Clear',2018,'Desarrollo personal',6,'Métodos y ejemplos para sostener cambios de hábitos.','9780735211292',6],
  ];
  foreach($books as [$title,$author,$year,$genre,$qty,$desc,$isbn,$tone]){
   Book::firstOrCreate(['title'=>$title],[
    'author'=>$author,'year'=>$year,'genre'=>$genre,'quantity'=>$qty,
    'copies_total'=>$qty,'description'=>$desc,'isbn'=>$isbn,'cover_tone'=>$tone,'is_active'=>true]);
  }
 }
}
