<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class EmployeeAttendanceWidget extends Widget
{
    protected string $view = 'filament.widgets.employee-attendance-widget';

    protected static ?int $sort = -2;

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', $today)
            ->first();

        return [
            'attendance' => $attendance,
            'user' => $user,
        ];
    }

    public static function canView(): bool
    {
        return ! auth()->user()->isAdmin();
    }
}
