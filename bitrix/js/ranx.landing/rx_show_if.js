(function()
{
    if(typeof window.RX != 'object')
        window.RX = {};

    if(typeof RX.ShowIf !== 'undefined')
        return;

    RX.ShowIf = {};


    //
    // OperatorBase
    //
    RX.ShowIf.OperatorBase = function(checker, operation)
    {
        this.checker = checker;
        this.check   = this.operationSolvers[operation];
    }

    RX.ShowIf.OperatorBase.prototype =
    {
        operationSolvers: {},
        getCheckingOptionKeys: function () { return {}; }
    }


    //
    // ValueOperator
    //
    RX.ShowIf.ValueOperator = function(checker, operation, key, values)
    {
        RX.ShowIf.OperatorBase.call(this, checker, operation);
        this.key    = key;
        this.values = values;

        if(!Array.isArray(this.values))
            this.values = [this.values];
    }

    RX.ShowIf.ValueOperator.prototype = Object.create(RX.ShowIf.OperatorBase.prototype);

    RX.ShowIf.ValueOperator.prototype.operationSolvers =
    {
        '=': function()
        {
            let option = this.checker.$scope.find('[data-option="' + this.key + '"]');
            let optionValue;

            if(option.is('input[type="checkbox"]'))
                optionValue = option.prop('checked');
            else
                optionValue = option.val();

            return this.values.indexOf(optionValue) >= 0;
        }
    };

    RX.ShowIf.ValueOperator.prototype.getCheckingOptionKeys = function()
    {
        return {[this.key]: true};
    }


    //
    // LogicOperator
    //
    RX.ShowIf.LogicOperator = function(checker, operation, operators)
    {
        RX.ShowIf.OperatorBase.call(this, checker, operation);
        this.operators = operators;
    }

    RX.ShowIf.LogicOperator.prototype = Object.create(RX.ShowIf.OperatorBase.prototype)

    RX.ShowIf.LogicOperator.prototype.operationSolvers =
    {
        'AND': function()
        {
            for(let i = 0; i < this.operators.length; i++)
                if(!this.operators[i].check())
                    return false;

            return true;
        },
        'OR': function()
        {
            for(let i = 0; i < this.operators.length; i++)
                if(this.operators[i].check())
                    return true;

            return false;
        },
    };

    RX.ShowIf.LogicOperator.prototype.getCheckingOptionKeys = function()
    {
        let keys = {};

        for(let i = 0; i < this.operators.length; i++)
            keys = $.extend(false, keys, this.operators[i].getCheckingOptionKeys());

        return keys;
    }


    //
    // ShowIfChecker
    //
    RX.ShowIf.ShowIfChecker = function(scope, node, visibilityHandler = null)
    {
        this.$scope            = $(scope);
        this.$node             = $(node);
        this.visibilityHandler = visibilityHandler;
        this.condition         = this.$node.data('show-if');
        this.rootOperator      = this.makeOperator(this.condition);

        let optionKeys     = this.rootOperator.getCheckingOptionKeys();
        let changeCallback = this.checkVisibility.bind(this);
        for(let key in optionKeys)
        {
            if(!optionKeys.hasOwnProperty(key))
                continue;

            this.$scope.find('[data-option="' + key + '"]').change(changeCallback);
        }

        this.checkVisibility();
    }

    RX.ShowIf.ShowIfChecker.prototype =
    {
        makeOperator: function(condition)
        {
            let logic          = 'AND';
            let childOperators = [];

            for(let key in condition)
            {
                if(!condition.hasOwnProperty(key))
                    continue;

                let value = condition[key];

                if(key === 'LOGIC')
                    logic = value;
                else if(!Array.isArray(value) && typeof value === 'object')
                    childOperators.push(this.makeOperator(value));
                else
                    childOperators.push(new RX.ShowIf.ValueOperator(this, '=', key, value));
            }

            return new RX.ShowIf.LogicOperator(this, logic, childOperators);
        },

        checkCondition: function ()
        {
            return this.rootOperator.check();
        },

        checkVisibility: function ()
        {
            let isVisible = this.checkCondition();

            if(this.visibilityHandler)
            {
                this.visibilityHandler.call(this.$node, isVisible);
                return;
            }

            if(isVisible !== this.$node.hasClass('show'))
                this.$node.toggleClass('show');
        }
    }

})();
