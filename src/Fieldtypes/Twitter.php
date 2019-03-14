<?php

namespace Statamic\Twitter\Fieldtypes;

use Statamic\CP\Column;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Statamic\Fields\Fieldtypes\Relationship;
use Thujohn\Twitter\Facades\Twitter as TwitterAPI;

class Twitter extends Relationship
{
    protected $canEdit = false;
    protected $canCreate = false;
    protected $indexComponent = 'twitter';
    protected $itemComponent = 'twitter-related-item';

    public function preProcessIndex($data)
    {
        return $this->augment($data);
    }

    public function augment($values)
    {
        return $this->getItemData($values);
    }

    protected function getColumns()
    {
        return [
            Column::make('text')->fieldtype('tweets'),
            Column::make('date')->value('date_relative'),
        ];
    }

    public function getIndexItems($request)
    {
        return $request->search ? $this->searchTweets($request->search) : $this->userTweets();
    }

    protected function searchTweets($query)
    {
        $user = $this->config('screen_name');

        $cacheKey = 'search-tweets-'.$user.'-'.$query;

        $tweets = Cache::remember($cacheKey, now()->addHour(), function () use ($user, $query) {
            return TwitterAPI::getSearch(['q' => 'from:' . $user . ' ' . $query]);
        });

        return $this->tweets($tweets->statuses);
    }

    protected function userTweets()
    {
        $user = $this->config('screen_name');

        $cacheKey = 'user-tweets-'.$user;

        $tweets = Cache::remember($cacheKey, now()->addHour(), function () use ($user) {
            return TwitterAPI::getUserTimeline(['screen_name' => $user]);
        });

        return $this->tweets($tweets);
    }

    public function getItemData($values, $site = null)
    {
        $values = collect($values);

        $cacheKey = 'tweet-data-' . md5($values->sort()->implode(''));

        $tweets = Cache::remember($cacheKey, now()->addHour(), function () use ($values) {
            return TwitterAPI::getStatusesLookup(['id' => $values->implode(',')]);
        });

        return $this->tweets($tweets);
    }

    public function tweets($tweets)
    {
        return collect($tweets)->map(function ($tweet) {
            $date = Carbon::parse($tweet->created_at);
            return [
                'id' => $tweet->id_str,
                'text' => $tweet->text,
                'date' => $date->timestamp,
                'date_relative' => $date->diffForHumans(),
                'user' => $tweet->user->screen_name,
            ];
        });
    }
}
