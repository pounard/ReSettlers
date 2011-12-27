Settlers Resource Resolver (ReSettlers)
=======================================

This library aims to be a resource usage resolver for the Settlers Online game.
It produces an internal representation of a *worker profile* with a specific
*resource capacity* and acts upon to resolve missing *workers* in order to
equilibrate the production flow and ensure production capacity is to its
maximum.

It has been originally designed to work the other way around, providing a
specific needed *resource* set to a resolver then creating an optimized *worker
profile* as output.

Concepts
========

ReSettlers\Component\Resource
-----------------------------

The *resource* object represent a particular resource.

ReSettlers\Component\Worker
---------------------------

The *worker* object represent a particular building that builds a specific
resource.

ReSettlers\Component\Provider
-----------------------------

The component provider concept has been extracted in order to give the API users
the ability to provide their own resources and workers definition storage.

Cycle
-----

Each *worker* has a production *cycle* (a duration expressed in seconds). Each
worker has its own cycle, this is why this resolver exists. 

ReSettlers\Component\Dependency
-------------------------------

A dependency represent the item count of a specific *resource* needed in order
to product another *resource* in one cycle.

Each *worker* instance carries a list of dependencies.

Capacity
--------

The *capacity* is an arbitrary float number that represent the number of items
build per second of a specific *resource*, either by a specific *worker* on
which we applied a certain *level upgrade*, either by a specific complete
*profile*.

ReSettlers\Profile\Profile
--------------------------

A *profile* object internally represent a set of *workers* of different levels
that builds different resources.

It is used by the *resolver* to compute the optimum production chain for a
specific set of *resources*.  

A profile can also be used as *input* for some *optimizers* instances as an
already existing user workers profile on which to merge with a resolver built
profile, in order to optimize an existing profile.
