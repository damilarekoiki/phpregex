#!/bin/sh

# Make sure scripts/pre-commit is executable
chmod +x scripts/pre-commit

# Symlink or copy to .git/hooks
if [ -d ".git/hooks" ]; then
    cp scripts/pre-commit .git/hooks/pre-commit
    chmod +x .git/hooks/pre-commit
    echo "Pre-commit hook installed successfully!"
else
    echo "Error: .git directory not found. Are you in the root of the repository?"
    exit 1
fi
