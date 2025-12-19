<?php

namespace App\Http\Controllers;

use App\Models\SeoSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class SeoController extends Controller
{
    /**
     * Display a listing of SEO settings.
     */
    public function index()
    {
        return view('admin.seo.index');
    }

    /**
     * Get SEO settings data for DataTables
     */
    public function data(Request $request)
    {
        $seoSettings = SeoSetting::select(['id', 'page_type', 'page_identifier', 'meta_title', 'meta_description', 'is_active', 'created_at']);

        return DataTables::of($seoSettings)
            ->addIndexColumn()
            ->addColumn('page_info', function ($seo) {
                $pageTypes = SeoSetting::getPageTypes();
                $pageTypeName = $pageTypes[$seo->page_type] ?? $seo->page_type;
                $identifier = $seo->page_identifier ? " ({$seo->page_identifier})" : '';
                return $pageTypeName . $identifier;
            })
            ->addColumn('meta_info', function ($seo) {
                $title = $seo->meta_title ? Str::limit($seo->meta_title, 50) : 'Не задано';
                $description = $seo->meta_description ? Str::limit($seo->meta_description, 80) : 'Не задано';
                return "<strong>Title:</strong> {$title}<br><small>Description:</small> {$description}";
            })
            ->addColumn('status_badge', function ($seo) {
                $badgeClass = $seo->is_active ? 'success' : 'secondary';
                $statusText = $seo->is_active ? 'Активно' : 'Неактивно';
                return '<span class="badge bg-' . $badgeClass . '">' . $statusText . '</span>';
            })
            ->addColumn('actions', function ($seo) {
                return '
                    <div class="dropdown">
                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Действия
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="' . route('admin.seo.edit', $seo->id) . '">
                                <i class="bx bx-edit me-2"></i> Редактировать
                            </a></li>
                            <li><a class="dropdown-item preview-seo" href="#" data-id="' . $seo->id . '">
                                <i class="bx bx-show me-2"></i> Предпросмотр
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger delete-seo" href="#" data-id="' . $seo->id . '">
                                <i class="bx bx-trash me-2"></i> Удалить
                            </a></li>
                        </ul>
                    </div>
                ';
            })
            ->rawColumns(['meta_info', 'status_badge', 'actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new SEO setting.
     */
    public function create()
    {
        $pageTypes = SeoSetting::getPageTypes();
        return view('admin.seo.create', compact('pageTypes'));
    }

    /**
     * Store a newly created SEO setting.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page_type' => 'required|string|max:255',
            'page_identifier' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'og_title' => 'nullable|string|max:70',
            'og_description' => 'nullable|string|max:160',
            'og_image' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url|max:255',
            'robots_meta' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Check for existing combination
            $exists = SeoSetting::where('page_type', $request->page_type)
                ->where('page_identifier', $request->page_identifier)
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->with('error', 'SEO настройки для данной страницы уже существуют!')
                    ->withInput();
            }

            $data = $request->except(['_token']);
            $data['is_active'] = $request->has('is_active');

            SeoSetting::create($data);

            return redirect()->route('admin.seo.index')
                ->with('success', 'SEO настройки успешно созданы!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Ошибка при создании SEO настроек: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified SEO setting.
     */
    public function edit($id)
    {
        $seoSetting = SeoSetting::findOrFail($id);
        $pageTypes = SeoSetting::getPageTypes();

        return view('admin.seo.edit', compact('seoSetting', 'pageTypes'));
    }

    /**
     * Update the specified SEO setting.
     */
    public function update(Request $request, $id)
    {
        $seoSetting = SeoSetting::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'page_type' => 'required|string|max:255',
            'page_identifier' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'og_title' => 'nullable|string|max:70',
            'og_description' => 'nullable|string|max:160',
            'og_image' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url|max:255',
            'robots_meta' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Check for existing combination (excluding current record)
            $exists = SeoSetting::where('page_type', $request->page_type)
                ->where('page_identifier', $request->page_identifier)
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->with('error', 'SEO настройки для данной страницы уже существуют!')
                    ->withInput();
            }

            $data = $request->except(['_token', '_method']);
            $data['is_active'] = $request->has('is_active');

            $seoSetting->update($data);

            return redirect()->route('admin.seo.index')
                ->with('success', 'SEO настройки успешно обновлены!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Ошибка при обновлении SEO настроек: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified SEO setting.
     */
    public function destroy($id)
    {
        try {
            $seoSetting = SeoSetting::findOrFail($id);
            $seoSetting->delete();

            return response()->json([
                'success' => true,
                'message' => 'SEO настройки успешно удалены!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении SEO настроек: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate preview of SEO in search results
     */
    public function preview($id)
    {
        try {
            $seoSetting = SeoSetting::findOrFail($id);

            $preview = [
                'title' => $seoSetting->getMetaTitleWithFallback(),
                'description' => $seoSetting->getMetaDescriptionWithFallback(),
                'url' => $seoSetting->canonical_url ?: url('/'),
                'schema' => $seoSetting->generateSchemaMarkup()
            ];

            return response()->json([
                'success' => true,
                'preview' => $preview
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при генерации предпросмотра: ' . $e->getMessage()
            ], 500);
        }
    }
}
