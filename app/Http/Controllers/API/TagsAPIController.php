<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\DB;
use Response;
use App\Models\Tags;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repositories\TagsRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\UpdateTagsAPIRequest;
use App\Http\Requests\API\CreateTagsAPIRequest;

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
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $tags = $this->tagsRepository->search(
            $request->input('name'),
            $request->input('page', 1),
            $request->input('limit', 15)
        );

        return result($tags, 'Tags retrieved successfully');
    }

    /**
     * Store a newly created Tags in storage.
     * POST /tags
     *
     * @param CreateTagsAPIRequest $request
     *
     * @return JsonResponse
     */
    public function store(CreateTagsAPIRequest $request)
    {
        $input = $request->all();
        $exits = Tags::whereIn('name', $input['name'])->get()->pluck('name')->toArray();
        if (!empty($exits)) {
            return error('不能重复添加，已存在标签名：' . implode(', ', $exits));
        }
        $createdNum = 0;
        $failNum = 0;
        foreach ($input['name'] as $value) {
            if ($this->tagsRepository->create(['name' => $value])) {
                $createdNum += 1;
            } else {
                $failNum += 1;
            }
        }
        return result(['created_num' => $createdNum, 'fail_num' => $failNum], 'Tags saved successfully');
    }

    /**
     * Display the specified Tags.
     * GET|HEAD /tags/{id}
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show($id)
    {
        /** @var Tags $tags */
        $tags = $this->tagsRepository->find($id);

        if (empty($tags)) {
            return error('Tags not found', 404);
        }

        return result($tags, 'Tags retrieved successfully');
    }

    /**
     * Update the specified Tags in storage.
     * PUT/PATCH /tags/{id}
     *
     * @param int $id
     * @param UpdateTagsAPIRequest $request
     *
     * @return JsonResponse
     */
    public function update($id, UpdateTagsAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Tags $tags */
        $tags = $this->tagsRepository->find($id);

        if (empty($tags)) {
            return error('Tags not found', 404);
        }

        $tags = $this->tagsRepository->update($input, $id);

        return result($tags, 'Tags updated successfully');
    }

    /**
     * Remove the specified Tags from storage.
     * DELETE /tags/{id}
     *
     * @param int $id
     *
     * @return JsonResponse
     * @throws \Exception
     *
     */
    public function destroy($id): JsonResponse
    {
        /** @var Tags $tags */
        $tags = $this->tagsRepository->find($id);

        if (empty($tags)) {
            return error('Tags not found', 404);
        }

        $tags->forceDelete();

        return result([], 'Tags deleted successfully');
    }
}
