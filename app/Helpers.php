<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

function executor()
{
    return auth()->check() ?
        auth()->user()->id :
        'SYSTEM';
}

function randomString($length = 8)
{
    return Str::random($length);
}

function randomDate($format = 'd/m/Y')
{
    $date = carbon()
        ->now()
        ->addDays(rand((-5), 5))
        ->format($format);

    return $date;
}

function carbon($parameter = null)
{
    return $parameter ? 
        new Carbon() : new Carbon($parameter);
}

function carbonStartOfDay($date)
{
    return Carbon::parse($date)->copy()->startOfDay();
}

function carbonEndOfDay($date)
{
    return Carbon::parse($date)->copy()->endOfDay();
}

function jsonResponse($response)
{
    return response()->json($response);
}

function apiResponse($repositoryObject, $responseData = null)
{
    return jsonResponse([
        'data' => $responseData,

        'status' => $repositoryObject->status,
        'message' => $repositoryObject->message,
        'query_error' => $repositoryObject->queryError,
    ]);
}

function uppercaseArray($array)
{
    return array_map('strtoupper', $array);
}

function flashMessage($repositoryObject)
{
    session()->flash(
        $repositoryObject->status, 
        ($repositoryObject->status == 'success') ? 
            $repositoryObject->message : 
            $repositoryObject->queryError
    );
}

function uploadFile($fileRequest, $filePath)
{
    $fileName = $filePath . Carbon::now()->format('YmdHis'); 
    $fileName .= $fileRequest->getClientOriginalName();

    $fileRequest->move(
        public_path($filePath), 
        $fileName
    );

    return $fileName;
}

function uploadBase64Image($base64Image, $imagePath = 'uploads/test')
{
    if(! File::exists($imagePath))
        File::makeDirectory($imagePath, $mode = 0755, true, true);

    $imageData = substr($base64Image, strpos($base64Image, ",") + 1);
    $imageData = base64_decode($imageData);
    $extension = explode('/', explode(':', substr($base64Image, 0, strpos($base64Image, ';')))[1])[1];

    // Prepare image path
    $imagePath = (substr($imagePath, -1) == '/') ?
        $imagePath : $imagePath . '/';
    $fileName = $imagePath . Carbon::now()->format('YmdHis') . '.' . $extension;

    File::put(public_path($fileName), $imageData);

    return File::put(public_path($fileName), $imageData) ? 
        $fileName : false;
}

function deleteFile($filePath)
{
    return File::delete($filePath);
}

function toRupiah($amount)
{
    $rupiah = (string) number_format($amount, 0, ',', '.');

    return 'Rp. ' . $rupiah;
}

function formatRupiah($amount)
{
    return toRupiah($amount);
}

function currentLink()
{
    return url()->current();
}

function requestMethod()
{
    return request()->method();
}

function isRoute($routeName)
{
    return Route::currentRouteName() == $routeName;
}

function isRouteStartsWith($start, $routeName = '')
{
    // if route is not defined make it current route
    $routeName = $routeName ? $routeName : Route::currentRouteName();

    return substr($routeName, 0, strlen($start)) == $start;
}

function createPagination($collections, $perPage = 10, $currentPage = 1)
{
    $pagination = new App\Repositories\PaginationRepository;

    return $pagination->paginateCollection(
        $collections, 
        $perPage, 
        $currentPage
    );
}

function generatePaginationLinks(
    $currentLink,
    array $urlParameters,
    $amountOfPage,
    $currentPage = 1
) {
    $link = [
        'prev_link' => '#',
        'next_link' => '#',
        'current_link' => '#',
        'urls' => [],
    ];
    $currentPage = isset($urlParameters['page']) ?
        $urlParameters['page'] : 1;
    $urlParameters['page'] = isset($urlParameters['page']) ?
        $urlParameters['page'] : $currentLink . '?page=' . $currentPage;

    for ($i = 1; $i <= $amountOfPage; $i++) {
        $iteration = 0;
        $amountOfParams = count($urlParameters);

        $link['urls'][$i] = $currentLink . '?';
        foreach ($urlParameters as $key => $parameter) {
            if ($key != 'page')
                $link['urls'][$i] .= $key . '=' . $parameter;
            else
                $link['urls'][$i] .= 'page' . '=' . $i;

            $iteration++;
            $link['urls'][$i] = $iteration != $amountOfParams ?
                $link['urls'][$i] . '&' : $link['urls'][$i];
        }

        if ($i == ($currentPage - 1))
            $link['prev_link'] = $link['urls'][$i];
        else if ($i == ($currentPage + 1))
            $link['next_link'] = $link['urls'][$i];
        else if ($i == ($currentPage))
            $link['current_link'] = $link['urls'][$i];
    }

    return $link;
}
