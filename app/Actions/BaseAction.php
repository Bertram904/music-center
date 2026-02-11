<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Throwable;

abstract class BaseAction
{
    /**
     * Execute the given callback within a Database transaction
     *
     * This method ensures data integrity. If any exception occurs during
     * the execution of the call back, the transaction is automatically
     * rolled back, and the error is logged for debugging
     *
     * @param callable $callback The business logic to execute.
     * @return mixed the result returned by the call back
     * @throws Throwable Re-throws the exception to be handled by the global handler.
     */
    protected function transaction(callable $callback) {
        DB::beginTransaction();
        try {
            //execute the core business logic
            $result = $callback();
            DB::commit();
            return $result;
        } catch (Exception $exception) {
            DB::rollBack();

            //Log the error with context
            Log::error("Action failed in " . static::class, [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
                'user_id' => auth('api')->id() ?? 'Guest',
            ]);

            throw $exception;
        }
    }
}
