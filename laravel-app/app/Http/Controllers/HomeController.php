<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Helpers\AnalyticsHelper;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth',]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (view()->exists('templates.' . $request->path())) {
            return view('templates.' . $request->path());
        }
        return abort(404);
    }

    public function root(Request $request)
    {
        // Return view without data - will be loaded via AJAX
        return view('admin.index');
    }

    /**
     * Get statistics data for AJAX requests
     */
    public function getStatistics(Request $request)
    {
        // Get date range from request or default to current month
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        // Calculate period length in days
        $periodStart = Carbon::parse($startDate);
        $periodEnd = Carbon::parse($endDate);
        $periodLength = $periodStart->diffInDays($periodEnd) + 1;

        // Calculate previous period dates
        $previousPeriodEnd = $periodStart->copy()->subDay();
        $previousPeriodStart = $previousPeriodEnd->copy()->subDays($periodLength - 1);

        // Calculate orders statistics for selected period
        $ordersCount = Order::whereBetween('created_at', [$startDate, $periodEnd->endOfDay()])
            ->count();
        $totalRevenue = Order::whereBetween('created_at', [$startDate, $periodEnd->endOfDay()])
            ->sum('total_amount');

        // Calculate revenue for selected period
        $periodRevenue = $totalRevenue;

        // Calculate previous period revenue for growth percentage
        $previousPeriodRevenue = Order::whereBetween('created_at', [
                $previousPeriodStart->format('Y-m-d'),
                $previousPeriodEnd->format('Y-m-d') . ' 23:59:59'
            ])
            ->sum('total_amount');

        // Calculate growth percentage
        $growthPercentage = 0;
        if ($previousPeriodRevenue > 0) {
            $growthPercentage = round((($periodRevenue - $previousPeriodRevenue) / $previousPeriodRevenue) * 100, 1);
        } elseif ($periodRevenue > 0) {
            $growthPercentage = 100; // If no previous period data but current period has revenue
        }

        // Analytics data
        $analyticsData = AnalyticsHelper::getSummary($startDate, $endDate);
        $siteVisitsChart = AnalyticsHelper::getSiteVisitsChartData($startDate, $endDate);
        $productViewsChart = AnalyticsHelper::getProductViewsChartData($startDate, $endDate, 10);

        return response()->json([
            'ordersCount' => $ordersCount,
            'totalRevenue' => $totalRevenue,
            'periodRevenue' => $periodRevenue,
            'previousPeriodRevenue' => $previousPeriodRevenue,
            'growthPercentage' => $growthPercentage,
            // Analytics
            'totalVisits' => $analyticsData['total_visits'],
            'uniqueVisitors' => $analyticsData['unique_visitors'],
            'productViews' => $analyticsData['product_views'],
            'topProducts' => $analyticsData['top_products'],
            'siteVisitsChart' => $siteVisitsChart,
            'productViewsChart' => $productViewsChart,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /*Language Translation*/
    public function lang($locale)
    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        } else {
            return redirect()->back();
        }
    }

    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        $user = User::find($id);
        $user->name = $request->get('name');
        $user->email = $request->get('email');

        if ($request->file('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatarPath = public_path('/images/');
            $avatar->move($avatarPath, $avatarName);
            $user->avatar = '/images/' . $avatarName;
        }

        $user->update();
        if ($user) {
            Session::flash('message', 'User Details Updated successfully!');
            Session::flash('alert-class', 'alert-success');
            return response()->json([
                'isSuccess' => true,
                'Message' => "User Details Updated successfully!"
            ], 200); // Status code here
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            return response()->json([
                'isSuccess' => true,
                'Message' => "Something went wrong!"
            ], 200); // Status code here
        }
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            return response()->json([
                'isSuccess' => false,
                'Message' => "Your Current password does not matches with the password you provided. Please try again."
            ], 200); // Status code
        } else {
            $user = User::find($id);
            $user->password = Hash::make($request->get('password'));
            $user->update();
            if ($user) {
                Session::flash('message', 'Password updated successfully!');
                Session::flash('alert-class', 'alert-success');
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "Password updated successfully!"
                ], 200); // Status code here
            } else {
                Session::flash('message', 'Something went wrong!');
                Session::flash('alert-class', 'alert-danger');
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "Something went wrong!"
                ], 200); // Status code here
            }
        }
    }
}
