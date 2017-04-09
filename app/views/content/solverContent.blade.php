<div class="rightWrapper">
<section role="main">
	<nav id="subBar">
		<h2>Uppgift {{ isset($data->task_data) ? $data->task_data->id : ' ej funnen' }}</h2>
		<ul>
			<li><a href="{{ $data->task_url }}">Uppgift</a></li>
			<li><a class="active" href="#">Lösning</a></li>
		</ul>
	</nav>
	<article role="solution">
		<table>
		      <tbody>
                <?php
                if (isset($data->task_data))
                {
                    if ($data->allowed)
                    {
                        $curr_data = json_decode($data->task_data->latex_solve);
                        for($i = 0; $i < count($curr_data); $i++)
                        {
                            $curr_numb = $i + 1;
                            echo '<tr>';
                                echo '<td>Steg '.$curr_numb.'</td>';
                                echo '<td>$$'.$curr_data[$i][0].'$$</td>';
                                echo '<td>'.$curr_data[$i][1].'</td>';
                            echo '</tr>';
                        }

                        echo '<tr id="answer"><td>Svar</td>';

                        foreach (json_decode($data->task_data->latex_answer) as $answer)
                            echo '<tr><td>'. $answer[0] .':</td><td>$$'. $answer[1] .'$$</td></tr>';
                        echo '</tr>';
                    }
                    else
                    {
                        echo '<tr><td>Du måste försöka med uppgiften innan du får se hur man gör!</td></tr>';
                    }
                }
                ?>
                
		    </tbody>
		</table>
		<div id="eq-kvotregeln-content" class="eq-content">
			$$y = \frac{f(x)}{g(x)} \Rightarrow y' = \frac{f'(x) \cdot g(x) - f(x) \cdot g'(x)}{g^2(x)}$$
		</div>
		<div class="center-content">
        <?php
        if ($data->allowed)
        {
        ?>
			<button id="next-step" class="button blue">Visa nästa steg</button>
			<button id="all-steps" class="button blue">Visa alla steg</button>
        <?php
        }
        ?>
		</div>
	</article>
</section>
</div>
