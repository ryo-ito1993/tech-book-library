<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Notifications\Notification;
use App\Library\CalilApiLibrary;
use App\Models\NotificationBook;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserNotificationBookMail;
use Illuminate\Support\Facades\Log;

class NotificationBookAvailability extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notification-book-availability';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $calilApiLibrary;

    public function __construct(CalilApiLibrary $calilApiLibrary)
    {
        parent::__construct();
        $this->calilApiLibrary = $calilApiLibrary;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $notificationBooks = NotificationBook::with('book', 'user.library')->get();

        foreach ($notificationBooks as $notificationBook) {
            $book = $notificationBook->book;
            $user = $notificationBook->user;
            $library = $user->library;

            try {
                $availability = $this->calilApiLibrary->checkBookAvailability($book->isbn, $library->system_id);

                $isAvailable = false;

                if (isset($availability['books'][$book->isbn]) && isset($availability['books'][$book->isbn][$library->system_id]['libkey'])) {
                    foreach ($availability['books'][$book->isbn][$library->system_id]['libkey'] as $status) {
                        if ($status === '貸出可') {
                            $isAvailable = true;
                            break;
                        }
                    }
                }

                if ($isAvailable) {
                    // ユーザーにメール通知を送信
                    Mail::to($user->email)->send(new UserNotificationBookMail($book, $user->library));

                    // 通知済みのレコードを削除
                    $notificationBook->delete();
                }

            } catch (HttpException $e) {
                Log::error('API request failed', ['message' => $e->getMessage(), 'isbn' => $book->isbn]);
                $this->error('API request failed: ' . $e->getMessage());
            } catch (Exception $e) {
                Log::error('An error occurred', ['message' => $e->getMessage(), 'isbn' => $book->isbn]);
                $this->error('An error occurred: ' . $e->getMessage());
            }
        }
        Log::info('NotificationBookAvailability command completed');
    }
}
