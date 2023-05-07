<?php

interface IChain
{
    public function addBlock(Block $block): static;
    public function getBlock(int $id): ?Block;
    public function getLastBlock(): ?Block;
    public function isValid(): bool;
}