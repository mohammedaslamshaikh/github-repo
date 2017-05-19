<?php

namespace AppBundle\Service;

use Github\ResultPager;

class GitHubApi
{
    private $client;

    public function __construct()
    {
        $this->client = new \Github\Client();
    }

    public function getProfile($username)
    {
        $data = $this->client->api('user')->show($username);
        
        $profileData = [
            'avatar_url' => $data['avatar_url'],
            'name' => $data['name'],
            'login' => $data['login'],
            'details' => [
                'company' => $data['company'],
                'location' => $data['location'],
                'joined_on' => 'Joined on ' . (new \DateTime($data['created_at']))->format('d-m-Y'),
            ],
            'blog' => $data['blog'],
            'social_data' => [
                "Public Repos" => $data['public_repos'],
                "Followers" => $data['followers'],
                "Following" => $data['following'],
            ]
        ];

        return $profileData;
    }

    public function getRepos($username)
    {
        $reposApi = $this->client->api('user');
        $paginator  = new ResultPager($this->client);
        $parameters = array($username);
        $data = $paginator->fetchAll($reposApi, 'repositories', $parameters);
        
        $reposData = [
            'repo_count' => count($data),
            'repos' => $data
        ];

        return $reposData;
    }
}