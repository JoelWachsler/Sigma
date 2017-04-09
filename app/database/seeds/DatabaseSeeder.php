<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		// $this->call('UserTableSeeder');
        $this->call('BookSeeder');
	}

}

/**
 * Custom seeder
 **/
class BookSeeder extends Seeder
{
    public function run()
    {
        DB::table('books')->delete();
        DB::table('chapters')->delete();
        DB::table('tasks')->delete();
        DB::table('formulas')->delete();
        DB::table('subchapters')->delete();

        // Create books and save id
        $first_book = Book::create(array(
            'name'      => '1c',
            'active'    => true
        ));

        $second_book = Book::create(array(
            'name'  => '2c'
        ));

        $third_book = Book::create(array(
            'name'  => '3c'
        ));

        $fourth_book = Book::create(array(
            'name'  => '4'
        ));

        $fifth_book = Book::create(array(
            'name'  => '5'
        ));

        $this->command->info('Books created');

        // Create chapters in first book
        $first_chapter = Chapter::create(array(
            'name'      => 'Tal',
            'book_id'   => $first_book->id,
            'desc'      => 'Tal i olika former'
        ));
        $second_chapter = Chapter::create(array(
            'name'      => 'Algebra och ekvationer',
            'book_id'   => $first_book->id,
            'desc'      => 'Algebra beskrivning'
        ));
        $third_chapter = Chapter::create(array(
            'name'      => 'Procent',
            'book_id'   => $first_book->id,
            'desc'      => 'procent beskrivning'
        ));
        $fourth_chapter = Chapter::create(array(
            'name'      => 'Funktioner',
            'book_id'   => $first_book->id,
            'desc'      => 'Algebra beskrivning'
        ));
        $fifth_chapter = Chapter::create(array(
            'name'      => 'Statistik',
            'book_id'   => $first_book->id,
            'desc'      => 'Statistik beskrivning'
        ));
        $sixth_chapter = Chapter::create(array(
            'name'      => 'Sannolikhetslära',
            'book_id'   => $first_book->id,
            'desc'      => 'Sannolikhetslära beskrivning'
        ));
        $seventh_chapter = Chapter::create(array(
            'name'      => 'Geometri',
            'book_id'   => $first_book->id,
            'desc'      => 'Geometri beskrivning'
        ));

        // Create chapters in second book
        $first_second_book_chapter = Chapter::create(array(
            'name'      => 'Geometri',
            'book_id'   => $second_book->id,
            'desc'      => 'Geometri beskrivning bok två'
        ));

        $this->command->info('Chapters created');

        // Create subchapter
        Subchapter::create(array(
            'name'          => 'Tal i olika former',
            'desc'          => 'Liten beskrivning',
            'chapter_id'    => $first_chapter->id
        ));

        Subchapter::create(array(
            'name'          => 'Lite andra saker',
            'desc'          => 'Liten beskrivning',
            'chapter_id'    => $second_chapter->id
        ));

        Subchapter::create(array(
            'name'          => 'Lite tredje saker',
            'desc'          => 'Liten beskrivning',
            'chapter_id'    => $third_chapter->id
        ));

        Subchapter::create(array(
            'name'          => 'Lite fjärde saker',
            'desc'          => 'Liten beskrivning',
            'chapter_id'    => $fourth_chapter->id
        ));

        Subchapter::create(array(
            'name'          => 'Lite femte saker',
            'desc'          => 'Liten beskrivning',
            'chapter_id'    => $fifth_chapter->id
        ));

        Subchapter::create(array(
            'name'          => 'Lite första saker i andra kapitlet',
            'desc'          => 'Liten beskrivning',
            'chapter_id'    => $first_second_book_chapter->id
        ));

        $this->command->info('Subchapters created');

        Type::create(array(
            'type_name'     => 'homework'
        ));
        Type::create(array(
            'type_name'     => 'classroom-new'
        ));

        $this->command->info('Types created');
    }
}
