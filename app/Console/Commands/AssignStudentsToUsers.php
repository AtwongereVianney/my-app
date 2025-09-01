<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\User;

class AssignStudentsToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:assign-to-users {--email-match : Match students to users by email} {--user-id= : Assign all unassigned students to a specific user ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign students to users for payment statistics access';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('email-match')) {
            $this->assignByEmailMatch();
        } elseif ($userId = $this->option('user-id')) {
            $this->assignToSpecificUser($userId);
        } else {
            $this->interactiveAssignment();
        }
        
        return 0;
    }

    private function assignByEmailMatch()
    {
        $this->info('Assigning students to users by email matching...');
        
        $students = Student::whereNull('user_id')->get();
        $users = User::all();
        
        $count = 0;
        foreach ($students as $student) {
            $user = $users->where('email', $student->email)->first();
            if ($user) {
                $student->update(['user_id' => $user->id]);
                $count++;
                $this->line("Assigned student '{$student->name}' to user '{$user->name}'");
            }
        }
        
        $this->info("Assigned {$count} students to users by email matching.");
    }

    private function assignToSpecificUser($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return;
        }
        
        $this->info("Assigning all unassigned students to user: {$user->name}");
        
        $count = Student::whereNull('user_id')->update(['user_id' => $userId]);
        
        $this->info("Assigned {$count} students to user '{$user->name}'.");
    }

    private function interactiveAssignment()
    {
        $this->info('Interactive student assignment to users...');
        
        $students = Student::whereNull('user_id')->get();
        $users = User::all();
        
        if ($students->isEmpty()) {
            $this->info('No unassigned students found.');
            return;
        }
        
        if ($users->isEmpty()) {
            $this->error('No users found. Please create users first.');
            return;
        }
        
        $this->table(
            ['ID', 'Name', 'Email'],
            $users->map(fn($user) => [$user->id, $user->name, $user->email])
        );
        
        foreach ($students as $student) {
            $this->line("\nStudent: {$student->name} ({$student->email})");
            
            $userId = $this->choice(
                'Select user to assign this student to:',
                $users->pluck('name', 'id')->toArray()
            );
            
            $student->update(['user_id' => $userId]);
            $this->info("Assigned student '{$student->name}' to user '{$users->find($userId)->name}'");
        }
    }
}
