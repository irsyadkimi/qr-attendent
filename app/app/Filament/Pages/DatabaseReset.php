<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseReset extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static string $view = 'filament.pages.database-reset';
    protected static ?string $navigationLabel = 'Reset Database';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $title = 'Reset Database (DANGER ZONE)';
    
    public $password_confirmation_1 = '';
    public $password_confirmation_2 = '';
    public $reset_guest_visits = false;
    public $reset_events = false;
    public $reset_participants = false;
    public $reset_attendances = false;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->role === 'admin' || $user->can_manage_users);
    }

    public function resetDatabase()
    {
        // Validate passwords
        if (empty($this->password_confirmation_1) || empty($this->password_confirmation_2)) {
            Notification::make()
                ->title('Password harus diisi!')
                ->danger()
                ->send();
            return;
        }

        if ($this->password_confirmation_1 !== $this->password_confirmation_2) {
            Notification::make()
                ->title('Password tidak sama!')
                ->danger()
                ->send();
            return;
        }

        // Check password
        if (!Hash::check($this->password_confirmation_1, auth()->user()->password)) {
            Notification::make()
                ->title('Password salah!')
                ->danger()
                ->send();
            return;
        }

        // Check if at least one selected
        if (!$this->reset_guest_visits && !$this->reset_events && !$this->reset_participants && !$this->reset_attendances) {
            Notification::make()
                ->title('Pilih minimal 1 tabel untuk direset!')
                ->warning()
                ->send();
            return;
        }

        try {
            DB::beginTransaction();
            
            $deleted = [];
            
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            if ($this->reset_guest_visits) {
                $count = DB::table('guest_visits')->count();
                DB::table('guest_visits')->truncate();
                $deleted[] = "Buku Tamu: $count records";
            }
            
            if ($this->reset_attendances) {
                $count = DB::table('attendances')->count();
                DB::table('attendances')->truncate();
                $deleted[] = "Attendances: $count records";
            }
            
            if ($this->reset_participants) {
                $count = DB::table('event_participants')->count();
                DB::table('event_participants')->truncate();
                $deleted[] = "Roster Peserta: $count records";
            }
            
            if ($this->reset_events) {
                $count = DB::table('events')->count();
                // Also delete participants & attendances for these events
                if (!$this->reset_participants) {
                    DB::table('event_participants')->truncate();
                }
                if (!$this->reset_attendances) {
                    DB::table('attendances')->truncate();
                }
                DB::table('events')->truncate();
                $deleted[] = "Events: $count records (+ related data)";
            }
            
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            DB::commit();

            Notification::make()
                ->title('Database berhasil direset!')
                ->body('Dihapus: ' . implode(', ', $deleted))
                ->success()
                ->send();

            // Clear form
            $this->password_confirmation_1 = '';
            $this->password_confirmation_2 = '';
            $this->reset_guest_visits = false;
            $this->reset_events = false;
            $this->reset_participants = false;
            $this->reset_attendances = false;

        } catch (\Exception $e) {
            DB::rollBack();
            
            Notification::make()
                ->title('Reset gagal!')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
