<?php

namespace Thor\Maybe;

/**
 * ### Maybe enumeration
 *
 * Describes possible nature of the Option.
 *
 * An Option can either be :
 * - a `Maybe::NONE` (contains no data),
 * - or a `Maybe::SOME` (contains some data).
 */
enum Maybe
{
    /**
     * The Option contains some data.
     */
    case SOME;

    /**
     * The Option contains no data.
     */
    case NONE;
}


