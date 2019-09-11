<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\AbstractController;
use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Jobs\Comment\CreateCommentJob;
use App\Jobs\Comment\DeleteCommentJob;
use App\Jobs\Comment\UpdateCommentJob;
use App\Models\Comment;
use App\Services\Cacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentsController extends AbstractController
{
    /**
     * @param Comment $comment
     * @return JsonResponse
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $this->authorize('delete', $comment);

        dispatch_now(new DeleteCommentJob($comment));

        return response()->json(['data' => null], 204);
    }

    /**
     * @return JsonResource
     */
    public function index(): JsonResource
    {
        $this->authorize('index', Comment::class);

        $comments = Cacher::collect(Comment::class);

        return CommentResource::collection($comments);
    }

    /**
     * @param Comment $comment
     * @return JsonResource
     */
    public function show(Comment $comment): JsonResource
    {
        $this->authorize('show', $comment);

        return new CommentResource($comment);
    }

    /**
     * @param CreateCommentRequest $request
     * @return JsonResponse
     */
    public function store(CreateCommentRequest $request): JsonResponse
    {
        $body = $request->input(CreateCommentRequest::BODY);

        $comment = dispatch_now(new CreateCommentJob($request->user()->id, $body));

        $resource = new CommentResource($comment);

        return $resource->response()
                        ->setStatusCode(201);
    }

    /**
     * @param UpdateCommentRequest $request
     * @param Comment $comment
     * @return JsonResource
     */
    public function update(UpdateCommentRequest $request, Comment $comment): JsonResource
    {
        $newBody = $request->input(UpdateCommentRequest::BODY);

        $comment = dispatch_now(new UpdateCommentJob($comment, $newBody));

        return new CommentResource($comment);
    }
}
