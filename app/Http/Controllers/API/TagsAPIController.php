<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTagsAPIRequest;
use App\Http\Requests\API\UpdateTagsAPIRequest;
use App\Models\Tags;
use App\Repositories\TagsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class TagsController
 * @package App\Http\Controllers\API
 */

class TagsAPIController extends AppBaseController
{
    /** @var  TagsRepository */
    private $tagsRepository;

    public function __construct(TagsRepository $tagsRepo)
    {
        $this->tagsRepository = $tagsRepo;
    }

    /**
     * Display a listing of the Tags.
     * GET|HEAD /tags
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $tags = $this->tagsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($tags->toArray(), 'Tags retrieved successfully');
    }

    /**
     * Store a newly created Tags in storage.
     * POST /tags
     *
     * @param CreateTagsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTagsAPIRequest $request)
    {
        $input = $request->all();

        $tags = $this->tagsRepository->create($input);

        return $this->sendResponse($tags->toArray(), 'Tags saved successfully');
    }

    /**
     * Display the specified Tags.
     * GET|HEAD /tags/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Tags $tags */
        $tags = $this->tagsRepository->find($id);

        if (empty($tags)) {
            return $this->sendError('Tags not found');
        }

        return $this->sendResponse($tags->toArray(), 'Tags retrieved successfully');
    }

    /**
     * Update the specified Tags in storage.
     * PUT/PATCH /tags/{id}
     *
     * @param int $id
     * @param UpdateTagsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTagsAPIRequest $request)
    {
        $input = $request->all();

        /** @var Tags $tags */
        $tags = $this->tagsRepository->find($id);

        if (empty($tags)) {
            return $this->sendError('Tags not found');
        }

        $tags = $this->tagsRepository->update($input, $id);

        return $this->sendResponse($tags->toArray(), 'Tags updated successfully');
    }

    /**
     * Remove the specified Tags from storage.
     * DELETE /tags/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Tags $tags */
        $tags = $this->tagsRepository->find($id);

        if (empty($tags)) {
            return $this->sendError('Tags not found');
        }

        $tags->delete();

        return $this->sendSuccess('Tags deleted successfully');
    }
}
